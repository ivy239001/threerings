<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unity_test";

// MySQLデータベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続をチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// カウントを増やす
$sql = "UPDATE button_counts SET count = count + 1 WHERE id = 1";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
