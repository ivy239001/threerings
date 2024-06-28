<?php
// session_start();
// if(isset($_SESSION['login'])==false){
//     print'ログインされていません。<br/>';
//     print'<a href="threerings/login/login.php">ログイン画面へ</a>';
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreeRings</title>
    <link rel="stylesheet" href="style.css">
    <script>
        var bgm = new Audio('../audio/BBBBダンス.mp3'); // BGMファイルのパスを指定してください
        var isMuted = false; // 音声がミュートされているかどうかの状態を管理
        var isPlaying = false; // BGMが再生中かどうかの状態を管理

        // ページ読み込み時にBGMを準備
        window.onload = function() {
            bgm.loop = true; // BGMをループ再生
            bgm.volume = 0.5; // 初期音量を設定 (0から1の範囲で設定可能)
        };

        // BGMの再生/停止を切り替える関数
        function toggleBGM() {
            if (isPlaying) {
                bgm.pause(); // 再生中なら停止
                isPlaying = false;
            } else {
                bgm.play(); // 停止中なら再生
                isPlaying = true;
            }
        }

        // 音量調整の関数
        function setVolume(volume) {
            bgm.volume = volume / 100; // 音量を設定 (0から1の範囲で設定するために100で割る)
        }

        // 音声のON/OFF切り替えの関数
        function toggleMute() {
            if (isMuted) {
                bgm.volume = 0.5; // ミュート解除時の音量を設定
                isMuted = false;
            } else {
                bgm.volume = 0; // ミュート時の音量を0に設定
                isMuted = true;
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
        <!-- 音声コントロールパネル -->
        <div class="audio-control">
            <button onclick="toggleBGM()">BGM ON/OFF</button>
            <input type="range" min="0" max="100" value="50" oninput="setVolume(this.value)">
        </div>
    </div>
</body>
</html>
