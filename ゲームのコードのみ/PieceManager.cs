using System.Collections.Generic;
using UnityEngine;
//"Large" ピースの値が 100の位、"Medium" ピースの値が 10の位、"Small" ピースの値が 1の位
//プレイヤー0,1,2,3に対し色は赤、青、緑、紫
public class PieceManager
{
    // ボードの状態を表す配列。0は空、1以上は各プレイヤーのピースが置かれていることを示す。
    public int[] Board { get; private set; }

    // 各サイズのピースの最大数と現在の数を管理する変数
    private int maxLargePieces = 3;
    private int maxMediumPieces = 3;
    private int maxSmallPieces = 3;
    private int currentLargePieces = 0;
    private int currentMediumPieces = 0;
    private int currentSmallPieces = 0;

    // コンストラクタでボードを初期化する
    public PieceManager()
    {
        // ボードの初期状態を全て0に設定する
        Board = new int[]
        {
            0, 0, 0,
            0, 0, 0,
            0, 0, 0,
        };
    }

    // ボードの状態をセットするメソッド
    public void SetBoard(int[] newBoard)
    {
        Board = newBoard;
    }

    // ボードの状態を取得するメソッド
    public int[] GetBoard()
    {
        return Board;
    }

    // ピースのサイズと色に基づいてピースの値を取得するメソッド
    public int GetPieceValue(string size, int player)
    {
        int baseValue = 0;
        switch (size)
        {
            case "Large":
                baseValue = 100;
                break;
            case "Medium":
                baseValue = 10;
                break;
            case "Small":
                baseValue = 1;
                break;
        }
        return baseValue * player;
    }

    // ピースを指定された位置に置けるかどうかをチェックするメソッド
    public bool CanPlacePiece(string size, int idx)
    {
        switch (size)
        {
            case "Large":
                return Board[idx] / 100 == 0 && currentLargePieces < maxLargePieces;
            case "Medium":
                return Board[idx] / 10 % 10 == 0 && currentMediumPieces < maxMediumPieces;
            case "Small":
                return Board[idx] % 10 == 0 && currentSmallPieces < maxSmallPieces;
            default:
                return false;
        }
    }

    public void PlacePiece(string size, int idx, int pieceValue)
    {
        // 指定された位置にピースの値を追加
        Board[idx] += pieceValue;

        // サイズに応じて現在のピースの残り数を更新
        switch (size)
        {
            case "Large":
                currentLargePieces++;
                break;
            case "Medium":
                currentMediumPieces++;
                break;
            case "Small":
                currentSmallPieces++;
                break;
        }
    }


    // プレイヤーとピースのサイズに応じてピースのプレハブを取得するメソッド
    public GameObject GetPiecePrefab(string size, int player, GameObject Large_R, GameObject Large_B, GameObject Large_G, GameObject Large_P, GameObject Medium_R, GameObject Medium_B, GameObject Medium_G, GameObject Medium_P, GameObject Small_R, GameObject Small_B, GameObject Small_G, GameObject Small_P)
    {
        switch (size)
        {
            case "Large":
                switch (player)
                {
                    case 0: return Large_R;
                    case 1: return Large_B;
                    case 2: return Large_G;
                    case 3: return Large_P;
                }
                break;
            case "Medium":
                switch (player)
                {
                    case 0: return Medium_R;
                    case 1: return Medium_B;
                    case 2: return Medium_G;
                    case 3: return Medium_P;
                }
                break;
            case "Small":
                switch (player)
                {
                    case 0: return Small_R;
                    case 1: return Small_B;
                    case 2: return Small_G;
                    case 3: return Small_P;
                }
                break;
        }
        return null;
    }

    // 勝利条件をチェックするメソッド
    public bool CheckForWin(int player)
    {
        // 勝利ラインを定義
        List<int[]> lines = new List<int[]>()
        {
            new int[] {0, 1, 2},
            new int[] {3, 4, 5},
            new int[] {6, 7, 8},
            new int[] {0, 3, 6},
            new int[] {1, 4, 7},
            new int[] {2, 5, 8},
            new int[] {0, 4, 8},
            new int[] {2, 4, 6}
        };

        // 同じ色で大中小のコマが同じマスにあるかをチェックする
        for (int i = 0; i < Board.Length; i++)
        {
            int value = Board[i];

            if (value / 100 == player && value / 10 % 10 == player && value % 10 == player)
            {
                return true;
            }
        }

        // ラインチェック
        foreach (var line in lines)
        {
            bool hasLarge = false, hasMedium = false, hasSmall = false;
            foreach (int index in line)
            {
                int value = Board[index];
                if (value / 100 == player) hasLarge = true;
                if (value / 10 % 10 == player) hasMedium = true;
                if (value % 10 == player) hasSmall = true;
            }
            if (hasLarge && hasMedium && hasSmall)
            {
                return true;
            }
        }
        return false;
    }

    // 引き分け条件をチェックするメソッド
    public bool CheckForDraw()
    {
        foreach (var value in Board)
        {
            if (value == 0)
            {
                return false;
            }
        }
        return true;
    }

    // ピースのカウントをリセットするメソッド
    public void ResetPieceCount()
    {
        currentLargePieces = 0;
        currentMediumPieces = 0;
        currentSmallPieces = 0;
    }

    // プレイヤーの色を取得するメソッド
    public Color GetPlayerColor(int player)
    {
        switch (player)
        {
            case 0: return Color.red;
            case 1: return Color.blue;
            case 2: return Color.green;
            case 3: return new Color(0.5f, 0f, 0.5f); // purple
            default: return Color.white;
        }
    }
}
