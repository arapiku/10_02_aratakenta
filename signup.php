<?php

include_once "templates/header.php";

// エラーメッセージ、サイナップメッセージの初期化
$errorMessage = "";
$signupMessage = "";
$Message = "";

// サイナップボタンが押されたら
if (isset($_POST["signUp"])) {
    // 必須項目の入力チェック
    if (empty($_POST['name'])) {
        $errorMessage = "ユーザー名が未入力です";
    } else if (empty($_POST['user_id'])) {
        $errorMessage = "ユーザーIDが未入力です";
    } else if (empty($_POST['email'])) {
        $errorMessage = "メールアドレスが未入力です";
    } else if (empty($_POST['password1'])) {
        $errorMessage = "パスワードが未入力です";
    } else if (empty($_POST['password2'])) {
        $errorMessage = "確認用パスワードが未入力です";
    }

    // 入力値に問題がない場合
    if (!empty($_POST['name']) && 
        !empty($_POST['user_id']) && 
        !empty($_POST['email']) && 
        !empty($_POST['password1']) && 
        !empty($_POST['password2']) && $_POST['password1'] == $_POST['password2']) {
        // 入力したユーザーIDとパスワードを取得
        $user_id = $_POST["user_id"];
        $nicname = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password1"];
        $profile = $_POST["profile"];

        /* ファイル処理ここから */
        if (isset($_FILES['avatar']) && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
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
                    header ('Location: signup.php');
                    exit();
            }
            $avatar_file = basename ( $_FILES ['avatar'] ['name'] );
            if (move_uploaded_file ( $old_avatar, 'uploads/' . $new_avatar )) {
                $Message = $avatar_file . 'のアップロードに成功しました';
            } else {
                $Message = 'アップロードに失敗しました';
            }
        } else {
            $new_avatar = 'default.png';
        }
        /* ファイル処理ここまで */

        /* エンティティ更新処理ここから */
        try { 
            $pdo = getDb();
            // ステートメントを用意
            $pre_query = 'INSERT INTO users(user_id, name, email, password, avatar, profile) VALUES(?, ?, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($pre_query);
            // ステートメントを実行
            $stmt->execute([$user_id, $nicname, $email, password_hash($password, PASSWORD_DEFAULT), $new_avatar, $profile]);
            // サイナップメッセージを用意
            $signupMessage = "登録が完了しました。あなたのユーザーIDは".$user_id."、パスワードは".$password."です。";

        } catch (PDOException $e) {
            $errorMessage = "DBエラー";
            var_dump($e->getMessage());
        }
        /* エンティティ更新処理ここまで */

    } else if (!empty($_POST['password1']) && !empty($_POST['password2']) && $_POST['password1'] != $_POST['password2']) {
        $errorMessage = "パスワードに誤りがあります。";
    }
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<h1>新規登録画面</h1>
<form id="loginForm" name="loginForm" action="" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>新規登録</legend>
        <div><font color="#ff0000"><?= h($errorMessage); ?></font></div>
        <div><font color="#0000ff"><?= h($signupMessage); ?></font></div>
        <div><font color="#0000ff"><?= h($Message); ?></font></div>
        <p>
            <label for="user_id">ユーザーID</label><input type="text" id="user_id" name="user_id" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["user_id"])) {echo h($_POST["user_id"]);} ?>">
        </p>
        <p>
            <label for="name">ユーザー名</label><input type="text" id="name" name="name" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["name"])) {echo h($_POST["name"]);} ?>">
        </p>
        <p>
            <label for="name">email</label><input type="email" id="email" name="email" placeholder="メールアドレスを入力" value="<?php if (!empty($_POST["email"])) {echo h($_POST["email"]);} ?>">
        </p>
        <p>
            <label for="password1">パスワード</label><input type="password" id="password1" name="password1" value="" placeholder="パスワードを入力">
        </p>
        <p>
            <label for="password2">パスワード(確認用)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
        </p>
        <p>
            <label for="avatar">アバター画像を選択</label><input type="file" id="avatar" name="avatar">
        </p>
        <p>
            <label for="profile">プロフィール</label><textarea name="profile" id="profile" cols="30" rows="10"><?php if (!empty($_POST["profile"])) {echo nl2br(h($_POST["profile"]));} ?></textarea>
        </p>
        <input type="submit" id="signUp" name="signUp" value="新規登録" class="button">
    </fieldset>
</form>
<br>
<form action="login.php">
    <input type="submit" value="TOPへ戻る" class="button">
</form>

<?php include_once "templates/footer.php"; ?> 