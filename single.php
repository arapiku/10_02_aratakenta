<?php

include_once "templates/header.php";

// ミーティングID
$mid = $_GET["id"];
// ユーザーID
$uid = $_SESSION["ID"];
//1．データ登録SQL作成
try { 
    $pdo = getDb();
    // ステートメントを用意
    $stmt = $pdo->prepare("SELECT * FROM meetings WHERE id = :mid");
    $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
    $stmt->execute();
    $meeting = $stmt->fetch();
    $status = $meeting["status"] ? '成立' : 'お茶する';

    // ステートメントを用意
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE meeting_id = :mid");
    $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll();

} catch (PDOException $e) {
    $errorMessage = "DBエラー";
    var_dump($e->getMessage());
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<p><?= h($meeting['title']) ?></p>
<button id="status_button" class="button"><?= h($status) ?></button>

<form action="message_save.php" method="post" id="comment_form">
    <p><input type="hidden" name="meeting_id" value="<?= h($mid)?>"></p>
    <p><label>メッセージ</label><textarea name="text" id="text" cols="30" rows="10" placeholder="メッセージを入力してね" required></textarea></p>
    <p><input type="submit" value="コメントする" class="button"></p>
</form>
<div id="search_results"></div>

<?php if($messages): ?>
<ul class="comment_list">
    <?php foreach($messages as $message): ?>
    <?php 
        // ステートメントを用意
        $id = $message["user_id"];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();
    ?>
    <li>
        <p style="background-image: url(uploads/<?=$user["avatar"]?>)" class="comment_avatar"><a href="profile.php?id=<?=$user["id"]?>"></a></p>
        <div class="comment_area">
            <p class="comment_text"><?= h($message["text"]) ?></p>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p>メッセージはまだありません。</p>
<?php endif; ?>

<?php include_once "templates/footer.php"; ?>