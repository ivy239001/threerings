<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // POSTデータの取得
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $new_pass = $_POST['new_password'];

    // 新しいパスワードのハッシュ化
    $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

    // SQLクエリの作成
    $sql = "UPDATE login_02 SET pass=? WHERE name=? AND mail=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $hashed_pass, $name, $mail);

    // クエリの実行
    if ($stmt->execute()) {
        header("Location: pass_success.php");
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
    <title>パスワード変更</title>
</head>
<body>
    <h2>パスワード変更</h2>
    <form method="post" action="pass.php">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="hidden" name="mail" value="<?php echo htmlspecialchars($mail); ?>">
        <label for="new_password">新しいパスワード:</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">更新</button>
    </form>
    <div class="back-button">
        <button onclick="location.href='myPage.php'">myPageへ戻る</button>
    </div>
</body>
</html>
