<?php
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
    $sql = "SELECT name, age, mail, my_shop, profile_image FROM garden_user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $mail = $_POST['mail'];
        $shop = $_POST['shop'];

        $sql = "UPDATE garden_user SET name = :name, age = :age, mail = :mail, my_shop = :shop WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':age', $age, PDO::PARAM_INT);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':shop', $shop, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Failed to update profile.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <link rel="stylesheet" type="text/css" href="css/profile.css" />
    <script src="js/mypage.js" defer></script>
    <script src="js/profile.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="headerLeft">
                <a href="index.php"><img src="img/garden_logo_orkney_font_fixed.png" alt=""></a>
            </div>
            <div class="headerRight">
            </div>
        </header>
        <div class="profileContainer">
            <div class="profileImage">
                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image">
            </div>
            <div class="profileInfo">
                <div class="profileInfoContent">
                    <div class="profileInfoContentLeft">
                        <p>Name</p>
                        <p>Age</p>
                        <p>Email</p>
                        <p>My Shop</p>
                    </div>
                    <div class="profileInfoContentCenter">
                        <p>：</p>
                        <p>：</p>
                        <p>：</p>
                        <p>：</p>
                    </div>
                    <div class="profileInfoContentRight">
                        <p><?php echo htmlspecialchars($user['name']); ?></p>
                        <p><?php echo htmlspecialchars($user['age']); ?></p>
                        <p><?php echo htmlspecialchars($user['mail']); ?></p>
                        <p><?php echo htmlspecialchars($user['my_shop']); ?></p>
                    </div>
                </div>
            </div>
            <div class="profileActions">
                <button id="back-btn">Back</button>
                <button id="edit-btn">Edit</button>
            </div>
        </div>
    </div>
</body>
</html>
