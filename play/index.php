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
                    　４人（もしくは２人で２色使用）でプレーするボードゲームです。<br>
                    　各プレーヤーはそれぞれ赤、青、緑、紫のリングを所持します。リングには大・中・小の３種類があり３つずつ所持しています。<br>
                    　手持ちのリングの中から１つ選択しボードに配置します（配置後移動はできません）。<br>
                    　異なるサイズのリングであれば１つのマスに配置することが可能です（同じサイズは配置できません）。<br>
                    　最も早く勝利条件となる配置をできたプレーヤーが勝利となります。
                    
                </div>
                <div class="rules">
                    <strong>ルール説明②</strong><br>
                    <br>
                    ３つのリングが以下のように並んだ場合プレイヤーは勝利となります。<br>
                    <br>
                    1⃣・同じ色で大・中・小または小・中・大の順番に縦・横・斜めのいずれか１列に並んだ場合<br>
                    <br>
                    <img src="../graphic/junnbann.png" alt="順番のリング" width="250" height="250"style="display: block; margin: auto;" />
                </div>
                <div class="rules">
                    <strong>ルール説明③</strong><br>
                    <br>
                    2⃣・同じ大きさで同じ色のリングが、縦・横・斜めのいずれかが１列に並んだ場合<br>
                    <br>
                    <img src="../graphic/onaji.png" alt="同じ大きさのリング" width="250" height="250"style="display: block; margin: auto;" />
                </div>
                <div class="rules">
                    <strong>ルール説明④</strong><br>
                    <br>
                    3⃣・同じ場所に同じ色で大・中・小の３種類のリングが置かれた場合<br>
                    <br>
                    <img src="../graphic/daisyou.png" alt="大・中・小のリング" width="250" height="250"style="display: block; margin: auto;" />
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
        var container = document.querySelector("#unity-container");
        var canvas = document.querySelector("#unity-canvas");
        var loadingBar = document.querySelector("#unity-loading-bar");
        var progressBarFull = document.querySelector("#unity-progress-bar-full");
        var fullscreenButton = document.querySelector("#unity-fullscreen-button");
        var warningBanner = document.querySelector("#unity-warning");

        // バナー表示関数
        function unityShowBanner(msg, type) {
            function updateBannerVisibility() {
                warningBanner.style.display = warningBanner.children.length ? 'block' : 'none';
            }
            var div = document.createElement('div');
            div.innerHTML = msg;
            warningBanner.appendChild(div);
            if (type == 'error') div.style = 'background: red; padding: 10px;';
            else {
                if (type == 'warning') div.style = 'background: yellow; padding: 10px;';
                setTimeout(function() {
                    warningBanner.removeChild(div);
                    updateBannerVisibility();
                }, 5000);
            }
            updateBannerVisibility();
        }

        var buildUrl = "Build";
        var loaderUrl = buildUrl + "/ThreeRings.loader.js";
        var config = {
            dataUrl: buildUrl + "/ThreeRings.data.unityweb",
            frameworkUrl: buildUrl + "/ThreeRings.framework.js.unityweb",
            codeUrl: buildUrl + "/ThreeRings.wasm.unityweb",
            streamingAssetsUrl: "StreamingAssets",
            companyName: "DefaultCompany",
            productName: "ThreeRings",
            productVersion: "1.0",
            showBanner: unityShowBanner,
        };

        if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
            // モバイルデバイス用のスタイル設定
            var meta = document.createElement('meta');
            meta.name = 'viewport';
            meta.content = 'width=device-width, height=device-height, initial-scale=1.0, user-scalable=no, shrink-to-fit=yes';
            document.getElementsByTagName('head')[0].appendChild(meta);
            container.className = "unity-mobile";
            canvas.className = "unity-mobile";
            unityShowBanner('WebGL builds are not supported on mobile devices.');
        } else {
            // デスクトップ用のスタイル設定
            canvas.style.width = "100%";
            canvas.style.height = "100%";
        }

        loadingBar.style.display = "block";

        var script = document.createElement("script");
        script.src = loaderUrl;
        script.onload = () => {
            createUnityInstance(canvas, config, (progress) => {
                progressBarFull.style.width = 100 * progress + "%";
            }).then((unityInstance) => {
                loadingBar.style.display = "none";
                fullscreenButton.onclick = () => {
                    unityInstance.SetFullscreen(1);
                };
            }).catch((message) => {
                alert(message);
            });
        };
        document.body.appendChild(script);
    </script>
</body>

</html>