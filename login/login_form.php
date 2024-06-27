<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <img src="akahazard.png" alt="ログイン画像" class="login-image">
        <div class="login-container">
            <h1>ログイン</h1>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="mail">メールアドレス：</label>
                    <input type="text" id="mail" name="mail" required>
                </div>
                <div class="input-group">
                    <label for="pass">パスワード：</label>
                    <input type="password" id="pass" name="pass" required>
                </div>
                <input type="submit" value="ログイン" class="btn">
            </form>
            <p>新規登録は<a href="signup.php">こちら</a></p>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
