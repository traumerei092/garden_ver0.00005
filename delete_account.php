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

    // ユーザー情報を削除
    $sql = "DELETE FROM garden_user WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        echo "Failed to delete account.";
    }

?>
