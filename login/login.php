<?php
session_start();

$dsn = "mysql:host=127.0.0.1; dbname=threerings; charset=utf8";
$username = "root";
$password = "";
$mail = $_POST['mail'];
$pass = $_POST['pass'];

try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $msg = 'ログインできません';
    $link = '<a href="login_form.php">戻る</a>';
    show_message_and_exit($msg, $link);
}

$sql = "SELECT * FROM login_02 WHERE mail = :mail";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':mail', $mail);
$stmt->execute();
$member = $stmt->fetch();

// デバッグ用コード：取得したユーザーデータを確認
if ($member === false) {
    $msg = 'メールアドレスが見つかりません';
    $link = '<a href="login_form.php">戻る</a>';
    show_message_and_exit($msg, $link);
}

// デバッグ用コード：パスワードが正しいか確認
if (password_verify($pass, $member['pass'])) {
    echo "パスワードが正しいです";
} else {
    echo "パスワードが間違っています";
    echo $member['pass'];
}

if ($member && password_verify($pass, $member['pass'])) {
    // DBのユーザー情報をセッションに保存
    $_SESSION['id'] = true;
    $_SESSION['name'] = $member['name'];
    header("Location: ../main/main.php"); // ログインが成功したらmainmanu.htmlにリダイレクト
    exit();
} else {
    $msg = 'ログインできないよ!!';
    $link = '<a href="login_form.php">戻る</a>';
    show_message_and_exit($msg, $link);
}

function show_message_and_exit($msg, $link) {
    echo "<!DOCTYPE html>
    <html lang='ja'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>ログイン結果</title>
        <link rel='stylesheet' href='styles.css'>
    </head>
    <body>
        <div class='container'>
            <h1>{$msg}</h1>
            {$link}
        </div>
    </body>
    </html>";
    exit();
}
?>
