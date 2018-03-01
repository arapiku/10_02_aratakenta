<?php
// セッションをスタート
session_start();

include_once "config/database.php";

// コメント送信ボタンが押されたら
if (isset($_POST["meeting_id"]) && isset($_POST["text"])) {
    
    $meeting_id = htmlspecialchars($_POST["meeting_id"], ENT_QUOTES);
    $text = htmlspecialchars($_POST["text"], ENT_QUOTES);

        // セッションからユーザーIDを取得
    $user_id = $_SESSION["ID"];

    try { 
        $pdo = getDb();
        // ステートメントを用意
        $pre_query = 'INSERT INTO messages(id, user_id, meeting_id, text) VALUES(NULL, :user_id, :meeting_id, :text)';
        $stmt = $pdo->prepare($pre_query);
        // バインドする
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':meeting_id', $meeting_id, PDO::PARAM_INT);
        $stmt->bindValue(':text', $text, PDO::PARAM_STR);
        // ステートメントを実行
        $stmt->execute();
        // コメントデータを返す
        $pre_query = 'SELECT * FROM messages WHERE meeting_id = :meeting_id';
        $stmt = $pdo->prepare($pre_query);
        // バインドする
        $stmt->bindValue(':meeting_id', $meeting_id, PDO::PARAM_INT);
        // ステートメントを実行
        $stmt->execute();
        var_dump($stmt);
        // single.phpに戻る
        header('Location: single.php?id='.$meeting_id);
    } catch (PDOException $e) {
        $errorMessage = "DBエラー";
        var_dump($e->getMessage());
    }
    
}