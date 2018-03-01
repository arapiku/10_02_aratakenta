<?php

include_once "templates/header.php";

$id = $_GET["id"];
  
//1．データ登録SQL作成
try { 
    $pdo = getDb();
    // ステートメントを用意
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute();
    $user = $stmt->fetch();

} catch (PDOException $e) {
    $errorMessage = "DBエラー";
    var_dump($e->getMessage());
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>
<p class="user_avatar" style="background-image:url(uploads/<?=$user["avatar"]?>)"></p>
<p><?=$user["name"]?></p>
<p><?=$user["profile"]?></p>

<?php include_once "templates/footer.php"; ?>