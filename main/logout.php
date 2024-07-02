<?php
session_start();

// セッションを破棄する
session_destroy();

// ログインページにリダイレクトする
header("Location: ../login/login_form.php");
exit();
?>
