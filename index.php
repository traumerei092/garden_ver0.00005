<?php

    // DB接続
    include('functions.php');
    $pdo = connect_db();

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GARDEN</title>
    <link rel="stylesheet" type="text/css" href="css/base.css" />
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <script src="js/index.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="headerLeft">
                <a href="index.php"><img src="img/garden_logo_orkney_font_fixed.png" alt=""></a>
            </div>
            <div class="headerRight" id="weather">
            </div>
        </header>
        <div class="topContainer">
            <div class="tabs">
                <button id="login-tab" class="tab-button active">Log in</button>
                <button id="signup-tab" class="tab-button">Sign Up</button>
            </div>
            <div class="form-container active" id="login-form-container">
                <form id="login-form" action="login.php" method="POST">
                    <h2>Log in</h2>
                    <input type="email" name="email" id="login-email" placeholder="Email" required>
                    <input type="password" name="password" id="login-password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials'): ?>
                    <p style="color: red;">Invalid email or password</p>
                <?php endif; ?>
            </div>
            <div class="form-container" id="signup-form-container">
                <form id="signup-form" action="signup.php" method="POST" enctype="multipart/form-data">
                    <h2>Sign Up</h2>
                    <input type="text" name="name" id="signup-name" placeholder="Name" required>
                    <input type="text" name="age" id="signup-age" placeholder="Age" required>
                    <input type="email" name="mail" id="signup-email" placeholder="Email" required>
                    <select name="shop" id="shop">
                        <option value="" selected hidden>My Shop</option>
                        <?php foreach ($enumValues as $value): ?>
                            <option value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="password" name="password" id="signup-password" placeholder="Password" required>
                    <input type="file" name="profile_image">
                    <button type="submit">Sign Up</button>
                    <a href=""></a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>