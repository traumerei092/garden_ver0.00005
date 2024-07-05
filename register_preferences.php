<?php
    session_start();

    // DB接続
    include('functions.php');
    $pdo = connect_db();

    // ユーザーIDをセッションから取得
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "User not logged in."]);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // POSTデータを取得
    $data = json_decode(file_get_contents("php://input"), true);
    $movie = $data['movie'];
    $sport = $data['sport'];
    $hobbies = implode(',', $data['hobbies']);

    // データベースに登録
    $sql = "UPDATE garden_user SET movie = :movie, sports = :sport, hobbies = :hobbies WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':movie', $movie, PDO::PARAM_STR);
    $stmt->bindValue(':sport', $sport, PDO::PARAM_STR);
    $stmt->bindValue(':hobbies', $hobbies, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
?>
