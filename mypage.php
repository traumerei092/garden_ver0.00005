<?php
    // セッション開始
    session_start();

    // DB接続
    include('functions.php');
    $pdo = connect_db();

    // ユーザーIDをセッションから取得
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // ユーザー情報を取得
    $sql = "SELECT my_shop, profile_image FROM garden_user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    $profile_image = $user['profile_image'];
    $my_shop = $user['my_shop'];

    // 同じ my_shop のユーザーのデータを取得
    $sql = "SELECT movie, sports, hobbies FROM garden_user WHERE my_shop = :my_shop";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':my_shop', $my_shop, PDO::PARAM_STR);
    $stmt->execute();
    $users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // データを集計
    $movies_count = [];
    $sports_count = [];
    $hobbies_count = [];

    foreach ($users_data as $data) {
        // 映画
        if (!isset($movies_count[$data['movie']])) {
            $movies_count[$data['movie']] = 0;
        }
        $movies_count[$data['movie']]++;

        // スポーツ
        if (!isset($sports_count[$data['sports']])) {
            $sports_count[$data['sports']] = 0;
        }
        $sports_count[$data['sports']]++;

        // 趣味
        $hobbies = explode(',', $data['hobbies']);
        foreach ($hobbies as $hobby) {
            if (!isset($hobbies_count[$hobby])) {
                $hobbies_count[$hobby] = 0;
            }
            $hobbies_count[$hobby]++;
        }
    }

    // コミュニティを取得
    $communities = get_user_communities($user_id);

    // JavaScriptに渡すためにJSONエンコード
    $movies_count_json = json_encode($movies_count);
    $sports_count_json = json_encode($sports_count);
    $hobbies_count_json = json_encode($hobbies_count);

    // コミュニティの検索
    $search_results = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search_query = $_POST['searchCommunity'] ?? '';
        if ($search_query) {
            $search_results = search_communities($search_query);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GARDEN YourPage</title>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <link rel="stylesheet" type="text/css" href="css/mypage.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/mypage.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="headerLeft">
                <a href="index.php"><img src="img/garden_logo_orkney_font_fixed.png" alt=""></a>
            </div>
            <div class="headerRight">
                <button id="profile-btn"><img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image"></button>
            </div>
        </header>
        <nav class="menu-bar">
            <ul>
                <li><a href="#" id="myshopTag" class="menubarTag <?= empty($search_results) ? 'active' : '' ?>">MyShop</a></li>
                <li><a href="#" id="communityTag" class="menubarTag <?= !empty($search_results) ? 'active' : '' ?>">Community</a></li>
                <li><a href="#" id="talkroomTag" class="menubarTag">TalkRoom</a></li>
                <li><a href="#" id="hogeTag" class="menubarTag">HogeHoge</a></li>
                <li><a href="#" id="fugaTag" class="menubarTag">FugaFuga</a></li>
            </ul>
        </nav>
        <div id="myshopContainer" class="mypage active">
            <div class="myShop">
                My Shop：<p><?= $my_shop ?></p>
            </div>
            <div class="charts-container">
                <h2>Favorite Movies</h2>
                <canvas id="movieChart"></canvas>
                <h2>Favorite Sports</h2>
                <canvas id="sportsChart"></canvas>
                <h2>Favorite Hobbies</h2>
                <canvas id="hobbiesChart"></canvas>
            </div>
        </div>
        <div id="communitiesContainer" class="mypage <?= !empty($search_results) ? 'active' : '' ?>">
            <h2>My Community</h2>
            <form class="searchCommunity" method="POST" action="">
                <input type="text" id="searchCommunity" name="searchCommunity" placeholder="Search Community" required>
                <button type="submit">
                    <img class="searchIcon" src="img/search_icon.png" alt="">
                </button>
            </form>
            <div class="viewSwitch">
                <button id="listView" class="tab-button active">
                    <img src="img/icon_list.png" alt="">
                </button>
                <button id="gridView" class="tab-button">
                    <img src="img/icon_grid.png" alt="">
                </button>
            </div>
            <div id="communityList" class="communityList active">
                <ul>
                    <?php foreach ($communities as $community): ?>
                        <li>
                            <a href="community.php?id=<?php echo htmlspecialchars($community['id']); ?>">
                                <?php echo htmlspecialchars($community['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="communityGrid" class="communityGrid">
                <div class="grid">
                    <?php foreach ($communities as $community): ?>
                        <div class="gridItem">
                            <a href="community.php?id=<?php echo htmlspecialchars($community['id']); ?>">
                                <?php echo htmlspecialchars($community['name']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="searchResults" class="searchResults">
                <h2>検索結果</h2>
                <ul>
                    <?php foreach ($search_results as $result): ?>
                        <li>
                            <a href="community.php?id=<?php echo htmlspecialchars($result['id']); ?>">
                                <?php echo htmlspecialchars($result['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- コミュニティ作成ボタン -->
            <button id="createCommunityButton">Create Community</button>
            <form id="createCommunityForm" class="createCommunityForm" method="POST" action="create_community.php">
                <input type="text" id="communityName" name="communityName" placeholder="Community Name" required>
                <button type="submit">Create Community</button>
            </form>

        </div>
        <div id="talkroomContainer" class="mypage">
            <h1>Talk Room</h1>
        </div>
        <div id="hogeContainer" class="mypage">
            <h1>Hoge Hoge</h1>
        </div>
        <div id="fugaContainer" class="mypage">
            <h1>Fuga Fuga</h1>
        </div>
    </div>
    <div id="sidebar" class="sidebar">
        <button id="close-btn">×</button>
        <ul>
            <li><a href="profile.php">プロフィール</a></li>
            <li><a href="#" id="logout-link">ログアウト</a></li>
            <li><a href="#" id="delete-account-link">退会</a></li>
        </ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const moviesCount = <?php echo $movies_count_json; ?>;
            const sportsCount = <?php echo $sports_count_json; ?>;
            const hobbiesCount = <?php echo $hobbies_count_json; ?>;

            // 映画のグラフを描画
            const movieCtx = document.getElementById('movieChart').getContext('2d');
            new Chart(movieCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(moviesCount),
                    datasets: [{
                        data: Object.values(moviesCount),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: 'Favorite Movies'
                        }
                    }
                }
            });

            // スポーツのグラフを描画
            const sportsCtx = document.getElementById('sportsChart').getContext('2d');
            new Chart(sportsCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(sportsCount),
                    datasets: [{
                        data: Object.values(sportsCount),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: 'Favorite Sports'
                        }
                    }
                }
            });

            // 趣味のグラフを描画
            const hobbiesCtx = document.getElementById('hobbiesChart').getContext('2d');
            new Chart(hobbiesCtx, {
                type: 'pie',
                data: {
                    labels: Object.keys(hobbiesCount),
                    datasets: [{
                        data: Object.values(hobbiesCount),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: 'Favorite Hobbies'
                        }
                    }
                }
            });

            // 検索結果がある場合は community タブを表示
            <?php if (!empty($search_results)): ?>
                document.getElementById('myshopTag').classList.remove('active');
                document.getElementById('myshopContainer').classList.remove('active');
                document.getElementById('communityTag').classList.add('active');
                document.getElementById('communitiesContainer').classList.add('active');
            <?php endif; ?>
        });
    </script>
</body>
</html>
