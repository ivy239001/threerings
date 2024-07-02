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
    <!-- ビューポート設定 -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
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
                <div>ユーザー名：xxx</div>
                <div>ログイン回数：xxx</div>
            </div>
            <div class="rules-container">
                <div class="rules">
                    <strong>ルール説明1</strong><br>
                    スタートプレイヤーを決めて、時計回りにリング配置を行います。<br>
                    自分のリングを１つ取り、ゲームエリア上の好きな箇所に配置します。<br>
                    リングを置いた後はそのリングを移動することは出来ません。<br>
                    自分の番にリングを置けない時はパスとなります。<br>
                </div>
                <div class="rules">
                    <strong>ルール説明2</strong><br>
                    ３つのリングが以下のように並んだ場合プレイヤーは勝利となります。<br>
                    １、大・中・小の順番に縦・横・斜めのいずれかに１列に並んだ場合<br>
                    ２、同じ大きさのリングを縦・横・斜めのいずれか１列に並んだ場合<br>
                    ３，同じ箇所・大・中・小の３種類のリングが置かれた場合<br>
                    以上３つが勝利条件となります。<br>
                    いずれかの条件に満たした場合、GoGoボタンを押したら勝利となります。<br>
                </div>
            </div>
            <div class="arrow-buttons">
                <!-- ルール切り替えボタン -->
                <button onclick="showNextRule()">次のルールへ</button>
            </div>
            <div class="back">
                <!-- メニューへ戻るボタン -->
                <button onclick="toggleRules()">ルールを表示／非表示</button>
            </div>
        </div>
    </div>

    <script>
        function toggleRules() {
            var rules = document.querySelectorAll('.rules');
            rules.forEach(rule => rule.classList.toggle('active'));
        }

        var currentRuleIndex = 0;
        var rules = document.querySelectorAll('.rules');

        function showNextRule() {
            // 現在のルールを非表示にする
            rules[currentRuleIndex].classList.remove('active');
            
            // 次のルールのインデックスを更新し、表示する
            currentRuleIndex = (currentRuleIndex + 1) % rules.length;
            rules[currentRuleIndex].classList.add('active');
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
