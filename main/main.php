<?php
session_start();
if (!isset($_SESSION['login'])) {
    print 'ログインされていません。<br/>';
    print '<a href="threerings/login/login.php">ログイン画面へ</a>';
    exit();
}

// エラーレポートをオンにする
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "172.16.3.130";
$username = "ivy_c239001";
$password = "";
$dbname = "threerings";
$port = 3306;

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_mail = $_SESSION['login'];

// SQLクエリの作成
$sql = "SELECT id, name, mail FROM login_02 WHERE mail = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $login_mail);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeRings</title>
    <link rel="stylesheet" href="style.css">
    <script>
        var bgm = new Audio('../audio/Mystic_Path.mp3'); // BGMファイルのパスを指定してください
        var isMuted = false;
        var isPlaying = false;

        window.onload = function() {
            bgm.loop = true;
            bgm.volume = 0.5;
            bgm.play();  // ページ読み込み時にBGMを再生
            isPlaying = true;
        };

        function toggleBGM() {
            if (isPlaying) {
                bgm.pause();
                isPlaying = false;
            } else {
                bgm.play();
                isPlaying = true;
            }
        }

        function setVolume(volume) {
            bgm.volume = volume / 100;
        }

        function toggleMute() {
            if (isMuted) {
                bgm.volume = 0.5;
                isMuted = false;
            } else {
                bgm.volume = 0;
                isMuted = true;
            }
        }

        function logout() {
            if (confirm('ログアウトしますか？')) {
                window.location.href = 'threerings/login/logout.php';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <img src="../graphic/logo.jpg" alt="ThreeRing Logo" class="title-logo">
        <div class="menu">
            <a href="../myPage/myPage.php">⚙ MyPage</a>
        </div>
        <div class="menu">
            <a href="../play/index.php">OffLine Play</a>
            <a href="#online">OnLine Play</a>
        </div>
        <div class="logout">
            <a href="logout.php">ログアウト</a>
        </div>

        <div class="audio-control">
            <button onclick="toggleBGM()">BGM ON/OFF</button>
            <input type="range" min="0" max="100" value="50" oninput="setVolume(this.value)">
        </div>
    </div>
</body>
</html>
