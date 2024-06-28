<?php
// session_start();
// if(isset($_SESSION['login']) == false){
//     print'ログインされていません。<br/>';
//     print'<a href="../login/login_form.php">ログイン画面へ</a>';
//     exit();
// }

// // エラーレポートをオンにする
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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
$sql = "SELECT name, mail FROM login_02";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>myPage</title>
    <script>
        function confirmDelete(name, mail) {
            if (confirm(`本当にユーザー ${name} (${mail}) を削除しますか？`)) {
                window.location.href = `delete.php?name=${name}&mail=${mail}`;
            }
        }
    </script>
</head>
<body>
    <h2>myPage情報</h2>
    <table>
        <tr>
            <th>名前</th>
            <th>メール</th>
            <th>操作</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // 出力データを行ごとに処理
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . htmlspecialchars($row["mail"]) . "</td><td><a href='edit.php?name=" . htmlspecialchars($row["name"]) . "&mail=" . htmlspecialchars($row["mail"]) . "'>編集</a> | <a href='javascript:void(0);' onclick=\"confirmDelete('" . htmlspecialchars($row["name"]) . "', '" . htmlspecialchars($row["mail"]) . "')\">削除</a></td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>データがありません</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <div class="back-button">
        <button onclick="location.href='../main/main.php'">戻る</button>
    </div>
</body>
</html>
