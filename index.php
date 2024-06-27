<?php
session_start();
$username = $_SESSION['name'];
if (isset($_SESSION['id'])) { //ログインしているとき
    header('Location:index.html');
    exit;
} else { //ログインしていない時
    $msg = 'ログインしていません';
    $link = '<a href="login_form.php">ログイン</a>';
}
?>
<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>