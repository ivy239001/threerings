<?php
//フォームからの値をそれぞれ変数に代入
$name = $_POST['name'];
$mail = $_POST['mail'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // 変数名を $pass から $pw に変更
$dsn = "mysql:host=172.16.3.130; dbname=threerings; charset=utf8";
$username = "ivy_c239001";
$password = "";
try {
    $dbh = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die("接続エラー: " . $e->getMessage());
}

//フォームに入力されたmailがすでに登録されていないかチェック
$sql = "SELECT * FROM login_02 WHERE mail = :mail";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':mail', $mail);
$stmt->execute();
$member = $stmt->fetch();
if ($member['mail'] === $mail) {
    $msg = '同じメールアドレスが存在します。';
    $link = '<a href="signup.php" class="btn">戻る</a>';
} else {
    //登録されていなければinsert 
    $sql = "INSERT INTO login_02(name, mail, pass) VALUES (:name, :mail, :pass)"; // :pass を :pw に変更
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':mail', $mail);
    $stmt->bindValue(':pass', $pass); // :pass を :pw に変更
    $stmt->execute();
    $msg = '会員登録が完了しました';
    $link = '<a href="login_form.php" class="btn">ログインページ</a>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録結果</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="result-container">
        <h1><?php echo $msg; ?></h1><!--メッセージの出力-->
        <?php echo $link; ?>
    </div>
</body>
</html>
