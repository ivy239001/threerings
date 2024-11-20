using UnityEngine;
using UnityEngine.Networking;
using System.Collections;

public class NetworkManager : MonoBehaviour
{
    void Start()
    {
        StartCoroutine(LoadBoardDataFromServer("1111")); // 例として roomId を指定
    }

    // サーバーからボードデータを取得するコルーチン
    private IEnumerator LoadBoardDataFromServer(string roomId)
    {
        WWWForm form = new WWWForm();
        form.AddField("room_id", roomId);

        using (UnityWebRequest www = UnityWebRequest.Post("http://localhost/unity_test/load_board.php", form))
        {
            yield return www.SendWebRequest();

            if (www.result != UnityWebRequest.Result.Success)
            {
                Debug.LogError("Failed to load board data: " + www.error);
            }
            else
            {
                string boardData = www.downloadHandler.text;
                Debug.Log("Board data loaded: " + boardData);

                // GameManager クラスのインスタンスを取得して、UpdateBoardFromData メソッドを呼び出す
                GameManager gameManager = FindObjectOfType<GameManager>();
                if (gameManager != null)
                {
                    gameManager.UpdateBoardFromData(boardData);
                }
                else
                {
                    Debug.LogError("GameManager not found.");
                }
            }
        }
    }

    // JSONデータを受け取るためのクラスはGameManagerクラスに移動
    public class BoardDataResponse
    {
        public string boardData;
    }
}
