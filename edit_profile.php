<?php
    session_start();

    // DB接続
    include('functions.php');
    $pdo = connect_to_db();

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

    // ENUM値を取得する関数
    function getEnumValues($pdo, $table, $field) {
        $query = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE '$field'");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $type = $row['Type'];
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enumValues = explode(',', $matches[1]);
        $enumValues = array_map(function($value) {
            return trim($value, "'");
        }, $enumValues);
        return $enumValues;
    }

    // ENUM値を取得
    $enumValues = getEnumValues($pdo, 'garden_user', 'my_shop');

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
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <link rel="stylesheet" type="text/css" href="css/edit_profile.css" />
    <script src="js/edit_profile.js" defer></script>
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
            <form class="profileInfo" action="edit_profile.php" method="POST">
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                <input type="text" name="age" id="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>
                <input type="email" name="mail" id="mail" value="<?php echo htmlspecialchars($user['mail']); ?>" required>
                <select name="shop" id="shop" required>
                    <option value="" hidden>My Shop</option>
                    <?php foreach ($enumValues as $value): ?>
                        <option value="<?php echo $value; ?>" <?php echo $user['my_shop'] == $value ? 'selected' : ''; ?>>
                            <?php echo ucfirst($value); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Update Profile</button>
            </form>
            <button id="back-btn" class="backButton">Back</button>
        </div>
    </div>
</body>
</html>
