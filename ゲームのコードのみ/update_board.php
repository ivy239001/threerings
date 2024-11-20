<?php
//update_board.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boardgame_db";

// 接続を作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続をチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $board = $_POST['board'];
    $current_player = $_POST['current_player'];
    $room_id = $_POST['room_id'];

    // デバッグ情報を出力
    error_log("ボード情報: $board");
    error_log("Received current_player: $current_player");

    $sql = "UPDATE game_state SET board='$board', current_player='$current_player' WHERE room_id='$room_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        // エラーメッセージを出力
        error_log("Error updating record: " . $conn->error);
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
