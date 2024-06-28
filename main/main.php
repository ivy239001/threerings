<?php
session_start();
if(isset($_SESSION['login'])==false){
    print'ログインされていません。<br/>';
    print'<a href="threerings/login/login.php">ログイン画面へ</a>';
    exit();
}

// エラーレポートをオンにする
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost"; // データベースサーバーのIPアドレスまたはホスト名
$username = "root"; // データベースユーザー名
$password = ""; // データベースパスワード
$dbname = "threerings"; // データベース名
$port = 3306; // データベースのポート番号

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeRings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <img src="../graphic/logo.jpg" alt="ThreeRing Logo" class="title-logo">
        <div class="menu">
            <a href="../setting/setting.php">⚙ MyPage</a>
        </div>
        <div class="menu">
            <a href="../play/index.html">OffLine Play</a>
            <a href="#online">OnLine Play</a>
        </div>
    </div>
</body>
</html>
