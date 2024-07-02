<?php
session_start();

$dsn = "mysql:host=172.16.3.130;dbname=threerings;charset=utf8";
$username = "ivy_c239001";
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

if ($member === false) {
    $msg = 'メールアドレスが見つかりません';
    $link = '<a href="login_form.php">戻る</a>';
    show_message_and_exit($msg, $link);
}

if (password_verify($pass, $member['pass'])) {
    // DBのユーザー情報をセッションに保存
    $_SESSION['login'] = $member['mail'];
    $_SESSION['name'] = $member['name'];

    // 現在のログイン回数を取得し、1を追加
    $logtimes = $member['logtimes'] + 1;

    // 更新クエリの準備
    $sql_update = "UPDATE login_02 SET logtimes = :logtimes WHERE mail = :mail";
    $stmt_update = $dbh->prepare($sql_update);
    $stmt_update->bindValue(':logtimes', $logtimes, PDO::PARAM_INT);
    $stmt_update->bindValue(':mail', $mail);
    $stmt_update->execute();

    // ログインが成功したらmainmanu.htmlにリダイレクト
    header("Location: ../main/main.php");
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
