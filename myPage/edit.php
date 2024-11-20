<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // エラーレポートをオンにする
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $servername = "localhost";
    $username = "root";

    $password = ""; // データベースパスワード
    $dbname = "threerings"; // データベース名
    $port = 3306; // データベースのポート番号

    // データベース接続の作成
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // 接続チェック
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // POSTデータの取得
    $old_name = $_POST['old_name'];
    $old_mail = $_POST['old_mail'];
    $new_name = $_POST['name'];
    $new_mail = $_POST['mail'];

    // SQLクエリの作成
    $sql = "UPDATE login_02 SET name=?, mail=? WHERE name=? AND mail=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $new_name, $new_mail, $old_name, $old_mail);

    // クエリの実行
    if ($stmt->execute()) {
        header("Location: update_success.php");
        exit();
    } else {
        echo "エラー: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $name = $_GET['name'];
    $mail = $_GET['mail'];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>編集ページ</title>
</head>

<body>
    <h2>ユーザー情報の編集</h2>
    <form method="post" action="edit.php">
        <input type="hidden" name="old_name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="hidden" name="old_mail" value="<?php echo htmlspecialchars($mail); ?>">
        <label for="name">名前:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <label for="mail">メール:</label>
        <input type="email" name="mail" id="mail" value="<?php echo htmlspecialchars($mail); ?>" required>
        <button type="submit">更新</button>
    </form>
    <form action="pass.php" method="get">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="hidden" name="mail" value="<?php echo htmlspecialchars($mail); ?>">
        <button type="submit">パスワードを変更する</button>
    </form>
    <div class="back-button">
        <button onclick="location.href='../myPage/myPage.php'">myPageへ戻る</button>
    </div>
</body>

</html>