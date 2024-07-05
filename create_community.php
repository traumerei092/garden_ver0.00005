<?php
    session_start();
    include('functions.php');
    $pdo = connect_db();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $community_name = $_POST['communityName'];
        $user_id = $_SESSION['user_id'];

        // コミュニティを作成
        $sql = "INSERT INTO communities (name, created_by) VALUES (:name, :created_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $community_name, PDO::PARAM_STR);
        $stmt->bindValue(':created_by', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // 新しく作成したコミュニティのIDを取得
        $community_id = $pdo->lastInsertId();

        // ユーザーをコミュニティに追加
        $sql = "INSERT INTO community_members (community_id, user_id, role) VALUES (:community_id, :user_id, 'admin')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':community_id', $community_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // リダイレクトしてリロード
        header("Location: mypage.php");
        exit();
    }
?>
