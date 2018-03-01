<?php

include_once "templates/header.php";

// エラーメッセージ、サイナップメッセージの初期化
$errorMessage = "";
$signupMessage = "";
$Message = "";

$id = $_SESSION["ID"];
$name = $_SESSION["NAME"];
$avatar = $_SESSION["AVATAR"];
  
//1．データ登録SQL作成
try { 
    $pdo = getDb();
    // ステートメントを用意
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute();
    // フェッチデータをuser変数に格納
    $user = $stmt->fetch();

} catch (PDOException $e) {
    $errorMessage = "DBエラー";
    var_dump($e->getMessage());
}

// フォーム設定
if (!empty($_POST['name']) || (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name']))) {
    // 入力したユーザーIDを取得
    if($_POST['name']) {
        $uname = $_POST["name"];
    } else {
        $uname = $_SESSION["NAME"];
    }
    var_dump($uname);

    if($_FILES ['avatar'] ['tmp_name']) {
        $old_avatar = $_FILES ['avatar'] ['tmp_name'];
        $new_avatar = date ( "YmdHis" );
        $new_avatar .= mt_rand ();
        switch (exif_imagetype ( $_FILES ['avatar'] ['tmp_name'] )) {
            case IMAGETYPE_JPEG :
                $new_avatar .= '.jpg';
                break;
            case IMAGETYPE_GIF :
                $new_avatar .= '.gif';
                break;
            case IMAGETYPE_PNG :
                $new_avatar .= '.png';
                break;
            default :
                header('Location: profile.php');
                exit();
        }
        $gazou = basename ( $_FILES ['avatar'] ['name'] );
        if (move_uploaded_file ( $old_avatar, 'uploads/' . $new_avatar )) {
            $Message = $gazou . 'のアップロードに成功しました';
        } else {
            $Message = 'アップロードに失敗しました';
        }
    } else {
        $new_avatar = $_SESSION["AVATAR"];
    }
    var_dump($new_avatar);

    try {
        // ステートメントを用意
        $pre_query = 'UPDATE users SET name = :name, avatar = :avatar WHERE id = :id';
        $stmt = $pdo->prepare($pre_query);
        $stmt->bindValue(':name', $uname, PDO::PARAM_STR);
        $stmt->bindValue(':avatar', $new_avatar, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        var_dump($pre_query);
        // ステートメントを実行
        $stmt->execute();
        // サイナップメッセージを用意
        $signupMessage = "ユーザー情報を更新しました";
        // セッション再取得のためにログアウト画面に送る
        header('Location: logout.php');

    } catch (PDOException $e) {
        $errorMessage = "DBエラー";
        var_dump($e->getMessage());
    }
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<form id="loginForm" name="loginForm" action="" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>ユーザー情報</legend>
        <?php if($user["avatar"]) : ?>
        <p class="user_avatar" style="background-image:url(./uploads/<?=$user["avatar"]?>)"></p>
        <?php endif; ?> 
        <div><font color="#ff0000"><?= h($errorMessage); ?></font></div>
        <div><font color="#0000ff"><?= h($signupMessage); ?></font></div>
        <div><font color="#0000ff"><?= h($Message); ?></font></div>
        <p>
            <label for="name">ユーザー名</label><input type="text" id="name" name="name" placeholder="ユーザー名を入力" value="<?= h($name) ?>">
        </p>
        <p>
            <label for="avatar">アバター画像を選択</label><input type="file" id="avatar" name="avatar">
        </p>
        <input type="hidden" name="id" value="<?= h($user["id"])?>">
        <input type="submit" id="signUp" name="signUp" value="変更する" class="button">
    </fieldset>
</form>

<?php include_once "templates/footer.php"; ?>