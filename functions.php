<?php
    // DB接続関数
    function connect_db() {
        $dsn = 'mysql:host=localhost;dbname=gs_d15_10;charset=utf8';
        $user = 'root';  // DBユーザー名
        $password = '';  // DBパスワード
        try {
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbh;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }

    // コミュニティ一覧を取得する関数
    function get_user_communities($user_id) {
        $dbh = connect_db();
        $sql = 'SELECT c.id, c.name, c.description FROM communities c
                JOIN community_members cm ON c.id = cm.community_id
                WHERE cm.user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // コミュニティメンバーを取得する関数
    function get_community_members($community_id) {
        $dbh = connect_db();
        $sql = 'SELECT u.id, u.username FROM garden_user u
                JOIN community_members cm ON u.id = cm.user_id
                WHERE cm.community_id = :community_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':community_id', $community_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // コミュニティ検索関数
    function search_communities($query) {
        $pdo = connect_db();
        $sql = "SELECT * FROM communities WHERE name LIKE :query";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>
