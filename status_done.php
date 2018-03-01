<?php

include_once "config/database.php";

if(!isset($_POST['status']) || !isset($_POST['id'])) {
    header('Location: single.php');
    exit();
}
if(isset($_POST['status']) && isset($_POST['id'])) {
    $id = htmlspecialchars($_POST["id"], ENT_QUOTES);
    $status = htmlspecialchars($_POST["status"], ENT_QUOTES);
    try {
        if($status == 1) {
            $status = 0;
            $pdo = getDb();
            // ステートメントを用意
            $pre_query = 'UPDATE meetings SET status = :status WHERE id = :id';
            $stmt = $pdo->prepare($pre_query);
            // バインドする
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':meeting_id', $meeting_id, PDO::PARAM_INT);
            $stmt->bindValue(':text', $text, PDO::PARAM_STR);
            // ステートメントを実行
            $stmt->execute();
        } else { 
            $status = 1;
        }
    } catch (PDOException $e) {
        $errorMessage = "DBエラー";
        var_dump($e->getMessage());
    }
}