<?php
// functions.php ファイル

// セッションチェック関数
function checkLogin() {
    if (!isset($_SESSION['login']) || !isset($_SESSION['name'])) {
        echo 'ログイン情報がありません。<br/>';
        echo '<a href="login_form.php">ログイン画面へ</a>';
        exit();
    }
}

// ログイン情報取得関数
function getLoginDetails() {
    return [$_SESSION['login'], $_SESSION['name']];
}

// データベース接続関数
function getDatabaseConnection() {
    $dsn = "mysql:host=localhost;dbname=threerings;charset=utf8";
    $username = "root";
    $password = "";
    try {
        return new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo 'データベース接続エラー: ' . $e->getMessage();
        exit();
    }
}

// ログイン回数取得関数
function getLoginCount($dbh, $login_mail) {
    $sql = "SELECT logtimes FROM login_02 WHERE mail = :mail";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':mail', $login_mail);
    $stmt->execute();
    $member = $stmt->fetch();
    if ($member === false) {
        echo 'ログイン情報が見つかりません。<br/>';
        echo '<a href="login_form.php">ログイン画面へ</a>';
        exit();
    }
    return $member['logtimes'];
}
?>
