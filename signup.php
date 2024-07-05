<?php

    // DB接続
    include('functions.php');
    $pdo = connect_db();

    // 画像アップロードの処理
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // 画像の保存先ディレクトリ
        $target_dir = "img/";
        $profile_image = $target_dir . "default_profile_image.png"; // デフォルトの画像パス
        $uploadOk = 1;

        if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == UPLOAD_ERR_OK) {
            $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // 画像が実際の画像かチェック
            $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // ファイルが既に存在するかチェック
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // ファイルサイズのチェック
            if ($_FILES["profile_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // 画像形式のチェック
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // エラーチェック後、ファイルをアップロード
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                    $profile_image = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // データベースに登録
        $name = $_POST['name'];
        $age = $_POST['age'];
        $mail = $_POST['mail'];
        $shop = $_POST['shop'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO garden_user (
                    name,
                    age,
                    mail,
                    my_shop,
                    password,
                    profile_image,
                    registered_date
                ) VALUES (
                    :name,
                    :age,
                    :mail,
                    :shop,
                    :password,
                    :profile_image,
                    now()
                )";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':age', $age, PDO::PARAM_INT);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':shop', $shop, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':profile_image', $profile_image, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // ユーザーIDを取得
            $user_id = $pdo->lastInsertId();
            // セッション開始
            session_start();
            $_SESSION['user_id'] = $user_id;
            // マイページにリダイレクト
            header("Location: mypage.php");
            exit();
        } else {
            echo "Sorry, there was an error inserting the data.";
        }
    }

?>
