<?php
session_start();
require_once 'functions.php'; // ユーティリティ関数をインクルード

//ここから本使用
// セッションチェックとログイン情報の取得
// checkLogin();
// list($login_mail, $user_name) = getLoginDetails(); // ログイン情報を取得

// データベース接続とログイン回数の取得
// $dbh = getDatabaseConnection(); // データベース接続
// $login_count = getLoginCount($dbh, $login_mail); // ログイン回数を取得
//ここまで本使用

//ここからローカルテスト用（本使用の時はコメント化）
$user_name = "ゲストユーザー";
$login_count = 5; // 仮のログイン回数

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Unity WebGL Player | ThreeRings</title>
    <link rel="shortcut icon" href="TemplateData/favicon.ico">
    <link rel="stylesheet" href="TemplateData/style.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS スタイルシートの定義 */
        .rules {
            display: none;
            /* 最初はすべての説明を非表示 */
        }

        .rules.active {
            display: block;
            /* active クラスが付いたときに表示する */
        }
    </style>
</head>

<body>
    <div id="main-container">
        <!-- Unity WebGL Player Section -->
        <div id="unity-container" class="unity-desktop">
            <canvas id="unity-canvas" width=800 height=600></canvas>
            <div id="unity-loading-bar">
                <div id="unity-logo"></div>
                <div id="unity-progress-bar-empty">
                    <div id="unity-progress-bar-full"></div>
                </div>
            </div>
            <div id="unity-warning"></div>
            <div id="unity-footer">
                <div id="unity-webgl-logo"></div>
                <div id="unity-fullscreen-button"></div>
                <div id="unity-build-title">ThreeRings</div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div id="sidebar">
            <div class="userinfo">
                <div>マイページ</div>
                <div>ユーザー名：<?php echo htmlspecialchars($user_name); ?></div> <!-- ユーザー名表示 -->
                <div>ログイン回数：<?php echo htmlspecialchars($login_count); ?></div> <!-- ログイン回数表示 -->
            </div>
            <div class="rules-container">
                <div class="rules active">
                    <strong>ルール説明①</strong><br>
                    <br>
                    スタートプレイヤーを決めて、順番にリング配置を行います。<br>
                    自分のリングを１つ選択し、ゲームエリア<br>上の好きな箇所に配置します。<br>
                    <br>
                    ※注意点としてリングを置いた後は<br>そのリングを移動することは出来ません。<br>
                    ※自分の番にリングを置けない時は<br>パスとなります。<br>
                </div>
                <div class="rules">
                    <strong>ルール説明②</strong><br>
                    <br>
                    ３つのリングが以下のように並んだ場合<br>プレイヤーは勝利となります。<br>
                    <br>
                    1⃣・大・中・小の順番に縦・横・斜めの<br>いずれか１列に並んだ場合<br>
                    <br>
                    <img src="../graphic/junnbann.png" alt="順番のリング" width="300" height="300">
                </div>
                <div class="rules">
                    <strong>ルール説明③</strong><br>
                    <br>
                    2⃣・同じ大きさのリングが、縦・横・<br>ななめのいずれかが１列に並んだ場合<br>
                    <br>
                    <img src="../graphic/onaji.png" alt="同じ大きさのリング" width="300" height="300">
                </div>
                <div class="rules">
                    <strong>ルール説明④</strong><br>
                    <br>
                    3⃣・同じ場所に・大・中・小の<br>３種類のリングが置かれた場合<br>
                    <br>
                    <img src="../graphic/daisyou.png" alt="大・中・小のリング" width="300" height="300">
                </div>
                <div class="rules">
                    <strong>ルール説明⑤</strong><br>
                    <br>
                    以上の３つが勝利条件となります。<br><br>
                    いずれかの条件に満たした場合<br>
                    GoGoボタンを押したら勝利となります。<br>
                </div>
            </div>
            <div class="arrow-buttons">
                <button onclick="showNextRule()">次のルールへ</button>
            </div>
            <div class="back">
                <button onclick="goToMainPage()">戻る</button>
            </div>

        </div>
    </div>

    <script>
        // JavaScript スクリプトの定義
        var rules = document.querySelectorAll('.rules');

        // 戻るボタン
        function goToMainPage() {
            window.location.href = '../main/main.php'; // メインページへのリダイレクト
        }

        // 次のルール表示ボタン
        function showNextRule() {
            var currentIndex = -1;
            rules.forEach(function(rule, index) {
                if (rule.classList.contains('active')) {
                    currentIndex = index;
                    rule.classList.remove('active'); // 現在のルールを非アクティブにする
                }
            });

            var nextIndex = (currentIndex + 1) % rules.length;
            rules[nextIndex].classList.add('active'); // 次のルールをアクティブにする
        }
    </script>
</body>

</html>