<?php

include_once "templates/header.php";

// パスワードをハッシュ化する
require('password.php');

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押されたら
if (isset($_POST['login'])) {
    // 値の空チェック
    if (empty($_POST['user_id'])){ 
        $errorMessage = "ユーザーIDが未入力です。";
    } else if (empty($_POST['password'])) {
        $errorMessage =  "パスワードが未入力です。";
    }

    // 正常終了時
    if(!empty($_POST['user_id']) && !empty($_POST['password'])) {
        // ユーザー名を格納
        $user_id = $_POST['user_id'];

        try { 
            $pdo = getDb();
            $pre_query = 'SELECT * FROM users WHERE user_id = ?';
            $stmt = $pdo->prepare($pre_query);
            $stmt->execute([$user_id]);
            $password = $_POST['password'];

            if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['password'])) {
                    // セッションidのリジェネ
                    session_regenerate_id(true);
                    // 入力したユーザー名を受け取る
                    $_SESSION['ID'] = $row['id'];
                    $_SESSION['USER_ID'] = $row['user_id'];
                    $_SESSION['NAME'] = $row['name'];
                    $_SESSION['AVATAR'] = $row['avatar'];
                    header('Location: index.php');
                    exit();
                } else {
                    // 認証失敗時
                    $errorMessage = "入力したユーザーIDまたはパスワードに誤りがあります。";
                }
            } else {
                // 該当データなし
                $errorMessage = "入力したユーザーIDまたはパスワードに誤りがあります。";
            }
        } catch (PDOException $e) {
            $errorMessage = "DBエラー";
            var_dump($e->getMessage());
        }
    }
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<form id="loginForm" name="loginForm" action="" method="POST">
    <fieldset>
        <legend>SIGN IN</legend>
        <div><font color="#ff0000"><?= h($errorMessage); ?></font></div>
        <p><label for="user_id">ユーザーID</label><input type="text" id="user_id" name="user_id" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["user_id"])) {echo h($_POST["user_id"]);} ?>">
        </p>
        <p><label for="password">PASSWORD</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
        </p>
        <input type="submit" id="login" name="login" value="ログイン" class="button">
    </fieldset>
</form>
<br>
<form id="signupForm" action="signup.php">
    <fieldset>
        <input type="submit" value="新規登録はこちら" class="button">
    </fieldset>
</form>

<?php include_once "templates/footer.php"; ?>