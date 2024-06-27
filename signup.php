<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="register-container">
        <h1 style="color: #d8cece;">新規会員登録</h1>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="name" style="color: #d8cece;">名前：</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="mail"style="color: #d8cece;">メールアドレス：</label>
                <input type="text" id="mail" name="mail" required>
            </div>
            <div class="input-group">
                <label for="pass"style="color: #d8cece;">パスワード：</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            <input type="submit" value="新規登録" class="btn">
        </form>
        <p>すでに登録済みの方は<a href="login_form.php">こちら</a></p>
    </div>
    <script src="scripts.js"></script>
</body>

</html>