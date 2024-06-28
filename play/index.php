<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Unity WebGL Player | ThreeRings</title>
    <link rel="shortcut icon" href="TemplateData/favicon.ico">
    <link rel="stylesheet" href="TemplateData/style.css">
    <!-- 自作のCSSファイルを追加 -->
    <link rel="stylesheet" href="styles.css">
    <?php

    $servername = "localhost"; // データベースサーバーのIPアドレスまたはホスト名
    $username = "root"; // データベースユーザー名
    $password = ""; // データベースパスワード
    $dbname = "threerings"; // データベース名
    $port = 3306; // データベースのポート番号
    
    // データベース接続の作成
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    
    // 接続チェック
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    

    // ユーザー名を取得するクエリ
    $user_id = 1; // 例としてユーザーIDを1とします
    $sql = "SELECT name FROM users WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 結果を連想配列として取得
        $row = $result->fetch_assoc();
        $username = $row["name"];
    } else {
        $username = "Unknown";
    }

    $conn->close();
    ?>
    <!-- PHPで取得したユーザー名を基にviewportを設定 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body>
    <!-- メインコンテナを追加して、Unityコンテナとサイドバーを含める -->
    <div id="main-container">
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
        <!-- 右側のサイドバーを追加 -->
        <div id="sidebar">
            <div class="userinfo">
                <div>マイページ</div>
                <div>ユーザー名：<?php echo $username; ?></div>
                <!-- 他のユーザー情報も必要に応じて表示 -->
            </div>
            <div class="rules">ルール説明</div>
            <div class="back">メニューへ戻る</div>
        </div>
    </div>
    <script>
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
