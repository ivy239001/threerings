<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    // GETパラメータから削除するユーザーの名前とメールを取得
    $name = $_GET['name'];
    $mail = $_GET['mail'];

    // SQLクエリの作成
    $sql = "DELETE FROM login_02 WHERE name=? AND mail=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $name, $mail);

    // クエリの実行
    if ($stmt->execute()) {
        // 削除成功時の処理
        header("Location: ../login/login_form.php"); // ログイン画面にリダイレクト
        exit();
    } else {
        echo "エラー: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
