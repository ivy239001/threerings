using UnityEngine;
using UnityEngine.SceneManagement;
using TMPro;
using UnityEngine.UI;
using Newtonsoft.Json;
using System.Collections;
using UnityEngine.Networking;
public class GameManager : MonoBehaviour
{
    // ピースのプレハブを参照するための変数
    public GameObject Large_R;
    public GameObject Large_B;
    public GameObject Large_G;
    public GameObject Large_P;
    public GameObject Medium_R;
    public GameObject Medium_B;
    public GameObject Medium_G;
    public GameObject Medium_P;
    public GameObject Small_R;
    public GameObject Small_B;
    public GameObject Small_G;
    public GameObject Small_P;

    // ゲーム結果表示用のオブジェクトとテキスト
    public GameObject resultObj;
    public TextMeshProUGUI infoTMP;

    // ボタンの参照
    public Button buttonLarge;
    public Button buttonMedium;
    public Button buttonSmall;
    public Button Reset;

    // 現在のプレイヤーとゲーム状態を保持する変数
    private int nowPlayer = 0;
    private bool win = false;
    private bool draw = false;
    private string selectedSize = "";

    // ピース管理クラスのインスタンス
    private PieceManager pieceManager;

    // ボード情報の更新間隔
    private float updateInterval = 5f;

    void Start()
    {
        // PieceManagerの初期化
        pieceManager = new PieceManager();

        // 初期ボード状態のログ出力
        Debug.Log("Initial Board State:");
        for (int i = 0; i < pieceManager.Board.Length; i++)
        {
            Debug.Log($"Board[{i}]: {pieceManager.Board[i]}");
        }

        // ボード情報をサーバーに送信
        StartCoroutine(SendBoardDataToServer());

        // ボード情報を定期的に更新する処理を開始
        InvokeRepeating("UpdateBoardPeriodically", updateInterval, updateInterval);

        // インフォテキストを更新
        UpdateInfoText();

        // ボタンのクリックイベントにリスナーを追加
        buttonLarge.onClick.AddListener(() => SelectSize("Large"));
        buttonMedium.onClick.AddListener(() => SelectSize("Medium"));
        buttonSmall.onClick.AddListener(() => SelectSize("Small"));
        Reset.onClick.AddListener(() => ResetGame());
    }

    // ボード情報を定期的に更新するメソッド
    private void UpdateBoardPeriodically()
    {
        Debug.Log("Updating Board Data...");
        LogBoardState("Before update:");
        StartCoroutine(LoadBoardDataFromServer());
        LogBoardState("After update:");
    }

    // ボードの状態をログに出力するメソッド
    private void LogBoardState(string message)
    {
        Debug.Log(message);
        for (int i = 0; i < pieceManager.Board.Length; i++)
        {
            Debug.Log($"Board[{i}]: {pieceManager.Board[i]}");
        }
    }

    // サーバーからボードデータを取得するコルーチン
    private IEnumerator LoadBoardDataFromServer()
    {
        WWWForm form = new WWWForm();
        form.AddField("room_id", "1111"); // 例としてroom_idを1111とする

        using (UnityWebRequest www = UnityWebRequest.Post("http://localhost/unity_test/load_board.php", form))
        {
            yield return www.SendWebRequest();

            if (www.result != UnityWebRequest.Result.Success)
            {
                Debug.Log(www.error);
            }
            else
            {
                string boardData = www.downloadHandler.text;
                Debug.Log("Board data loaded: " + boardData);

                // 取得したボードデータを解析して更新
                UpdateBoardFromData(boardData);
            }
        }
    }

    // 取得したボードデータを解析して更新するメソッド
    public void UpdateBoardFromData(string boardData)
    {
        var responseData = JsonConvert.DeserializeObject<BoardDataResponse>(boardData);
        string[] boardDataArray = responseData.boardData.Split(',');
        int[] newBoard = new int[boardDataArray.Length];

        for (int i = 0; i < boardDataArray.Length; i++)
        {
            if (int.TryParse(boardDataArray[i], out int value))
            {
                newBoard[i] = value;
            }
            else
            {
                Debug.LogError("Invalid board data: " + boardDataArray[i]);
                return;
            }
        }

        pieceManager.SetBoard(newBoard);
    }

    // JSONデータを受け取るためのクラス
    public class BoardDataResponse
    {
        public string boardData;
    }

    void Update()
    {
        if (win || draw) return; // ゲームが終了している場合、更新しない

        bool next = false; // 次のプレイヤーに進むかどうかを判断するフラグ

        if (Input.GetMouseButtonUp(0) || (Input.touchCount > 0 && Input.GetTouch(0).phase == TouchPhase.Ended))
        {
            Vector3 inputPosition = Input.touchCount > 0 ? Input.GetTouch(0).position : Input.mousePosition;
            Ray ray = Camera.main.ScreenPointToRay(inputPosition);
            RaycastHit2D hit = Physics2D.Raycast(ray.origin, ray.direction);

            if (hit.collider != null)
            {
                // クリックした座標を配列に変換
                Vector3 pos = hit.collider.gameObject.transform.position;
                int x = (int)pos.x + 1;
                int y = (int)pos.y + 1;
                int idx = x + y * 3;
                //サイズを選ばずにクリックした処理
                if (string.IsNullOrEmpty(selectedSize))
                {
                    Debug.Log("サイズを選択してください");
                    Debug.Log("Board value: " + pieceManager.Board[idx]);
                }
                else
                {
                    int pieceValue = pieceManager.GetPieceValue(selectedSize, nowPlayer + 1);

                    if (pieceManager.CanPlacePiece(selectedSize, idx))
                    {
                        pieceManager.PlacePiece(selectedSize, idx, pieceValue);
                        InstantiatePiece(pos);
                        StartCoroutine(SendBoardDataToServer()); // 駒を置いた後にボードデータをサーバーに送信
                        next = true;
                    }
                    else
                    {
                        Debug.Log("そこには置けません");
                        Debug.Log("Board value: " + pieceManager.Board[idx]);
                    }
                }
            }
        }

        if (next)
        {
            CheckForWin(); // 勝利条件のチェック
            if (!win)
            {
                CheckForDraw(); // 引き分け条件のチェック
                nowPlayer = (nowPlayer + 1) % 4; // 次のプレイヤーに進む
                UpdateInfoText(); // インフォテキストを更新
                pieceManager.ResetPieceCount(); // ピースのカウントをリセット
            }
        }
    }

    // ピースをインスタンス化するメソッド
    private void InstantiatePiece(Vector3 pos)
    {
        GameObject prefab = pieceManager.GetPiecePrefab(selectedSize, nowPlayer, Large_R, Large_B, Large_G, Large_P, Medium_R, Medium_B, Medium_G, Medium_P, Small_R, Small_B, Small_G, Small_P);
        Instantiate(prefab, pos, Quaternion.identity);
        selectedSize = "";
        ResetButtonBorders();
    }

    // 現在のプレイヤー情報を表示するテキストを更新するメソッド
    private void UpdateInfoText()
    {
        string[] playerColors = { "red", "blue", "green", "purple" };
        infoTMP.text = $"<color={playerColors[nowPlayer]}>Player {nowPlayer + 1}</color>'s Turn";
    }

    // 勝利条件をチェックするメソッド
    private void CheckForWin()
    {
        win = pieceManager.CheckForWin(nowPlayer + 1);
        if (win) ShowResult(nowPlayer + 1);
    }

    // 引き分け条件をチェックするメソッド
    private void CheckForDraw()
    {
        draw = pieceManager.CheckForDraw();
        if (draw)
        {
            resultObj.SetActive(true);
            infoTMP.text = "Draw!";
        }
    }

    // 勝利結果を表示するメソッド
    private void ShowResult(int player)
    {
        resultObj.SetActive(true);
        infoTMP.text = player + "P Win!";
    }

    // リトライボタンが押されたときにシーンを再読み込みするメソッド
    public void OnClickRetry()
    {
        SceneManager.LoadScene("SampleScene");
    }

    // ピースサイズを選択するメソッド
    public void SelectSize(string size)
    {
        selectedSize = size;
        UpdateButtonBorders();
    }

    // 選択したボタンの枠を更新するメソッド
    private void UpdateButtonBorders()
    {
        Color playerColor = pieceManager.GetPlayerColor(nowPlayer);

        switch (selectedSize)
        {
            case "Large":
                ResetButtonBorders();
                buttonLarge.GetComponent<Image>().color = playerColor;
                break;
            case "Medium":
                ResetButtonBorders();
                buttonMedium.GetComponent<Image>().color = playerColor;
                break;
            case "Small":
                ResetButtonBorders();
                buttonSmall.GetComponent<Image>().color = playerColor;
                break;
        }
    }

    // ボタンの枠をリセットするメソッド
    private void ResetButtonBorders()
    {
        buttonLarge.GetComponent<Image>().color = Color.white;
        buttonMedium.GetComponent<Image>().color = Color.white;
        buttonSmall.GetComponent<Image>().color = Color.white;
    }

    // ゲームをリセットするメソッド
    public void ResetGame()
    {
        pieceManager = new PieceManager();
        SceneManager.LoadScene(SceneManager.GetActiveScene().name);
    }

    // ボードデータをサーバーに送信するコルーチン
    private IEnumerator SendBoardDataToServer()
    {
        string boardData = string.Join(",", pieceManager.Board);
        string playerData = nowPlayer.ToString();
        string roomId = "1111"; // 例としてroom_idを1111とする

        WWWForm form = new WWWForm();
        form.AddField("board", boardData);
        form.AddField("current_player", playerData);
        form.AddField("room_id", roomId);

        using (UnityWebRequest www = UnityWebRequest.Post("http://localhost/unity_test/update_board.php", form))
        {
            yield return www.SendWebRequest();

            if (www.result != UnityWebRequest.Result.Success)
            {
                Debug.Log(www.error);
            }
            else
            {
                Debug.Log("Board data sent to server successfully.");
            }
        }
    }
}
