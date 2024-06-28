<?php
session_start();
if(isset($_SESSION['login'])==false){
    print'ログインされていません。<br/>';
    print'<a href="../login/login_form.php">ログイン画面へ</a>';
    exit();
}

// エラーレポートをオンにする
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "172.16.3.130"; // データベースサーバーのIPアドレスまたはホスト名
$username = "ivy_c239001"; // データベースユーザー名
$password = ""; // データベースパスワード
$dbname = "threerings"; // データベース名
$port = 3306; // データベースのポート番号

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQLクエリの作成
$sql = "SELECT name, mail FROM login_02"



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="style.css"></script>
    <title>Document</title>
</head>
<body>
<ul>
</ul>
    
    
</body>
</html>