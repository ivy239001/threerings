<?php
//load_board.php
// データベース接続情報
$servername = "localhost";
$username = "root"; // データベースのユーザー名
$password = ""; // データベースのパスワード
$dbname = "boardgame_db"; // データベース名

// POST リクエストから room_id を取得
$room_id = $_POST['room_id'];

// データベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// room_id を使ってボードの情報を取得するクエリ
$sql = "SELECT room_id, board FROM game_state WHERE room_id = '$room_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // データが見つかった場合、最初の行の board データを取得
    $row = $result->fetch_assoc();
    $boardData = $row['board'];

    // JSON形式のデータを連想配列に変換
    $boardArray = json_decode($boardData, true);

    // JSONデータを出力
    echo json_encode(['boardData' => $boardData]);

} else {
    // データが見つからなかった場合の処理（エラーなど）
    echo "0 results";
}

$conn->close();
?>
