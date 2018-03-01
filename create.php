<?php
include_once "templates/header.php";

// エラーメッセージの初期化
$errorMessage = "";

// サイナップボタンが押されたら
if (isset($_POST["create"])) {
    // 必須項目チェック
    if (empty($_POST['title'])) {
        $errorMessage = "タイトルが未入力です";
    } else if (empty($_POST['prefecture'])) {
        $errorMessage = "都道府県が未入力です";
    } else if (empty($_POST['region'])) {
        $errorMessage = "地域が未入力です";
    } else if (empty($_POST['place'])) {
        $errorMessage = "場所が未入力です";
    } else if (empty($_POST['category'])) {
        $errorMessage = "カテゴリが未入力です";
    } else if (empty($_POST['meeting_at'])) {
        $errorMessage = "開催時間紙入力です";
    } else if (empty($_POST['body'])) {
        $errorMessage = "ミーティング内容が未入力です";
    }

    // 入力値に問題がなければ以下実行
    if (!empty($_POST['title']) && 
        !empty($_POST['body']) && 
        !empty($_POST['prefecture']) && 
        !empty($_POST['region']) && 
        !empty($_POST['place']) &&
        !empty($_POST['category']) &&
        !empty($_POST['meeting_at'])){
        // 入力したユーザーIDとパスワードを取得
        $title = $_POST["title"];
        $body = $_POST["body"];
        $prefecture = $_POST["prefecture"];
        $region = $_POST["region"];
        $place = $_POST["place"];
        $category = $_POST["category"];
        $meeting_at = $_POST["meeting_at"];
        // ユーザーIDもセット
        $user_id = $_SESSION['ID'];

        /* エンティティ更新処理ここから */
        try { 
            $pdo = getDb();
            // ステートメントを用意
            $pre_query = 'INSERT INTO meetings(user_id, title, body, prefecture, region, place, category, meeting_at)
                         VALUES(:user_id, :title, :body, :prefecture, :region, :place, :category, :meeting_at)';
            $stmt = $pdo->prepare($pre_query);
            // バインドする
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':body', $body, PDO::PARAM_STR);
            $stmt->bindValue(':prefecture', $prefecture, PDO::PARAM_STR);
            $stmt->bindValue(':region', $region, PDO::PARAM_STR);
            $stmt->bindValue(':place', $place, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':meeting_at', $meeting_at, PDO::PARAM_STR);
            // ステートメントを実行
            $stmt->execute();
            // トップに遷移
            header('Location: index.php');

        } catch (PDOException $e) {
            $errorMessage = "DBエラー";
            var_dump($e->getMessage());
        }
        /* エンティティ更新処理ここまで */
    }

}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<h1>新規登録画面</h1>
<form id="loginForm" name="loginForm" action="" method="POST">
    <fieldset>
        <legend>新規登録</legend>
        <div><font color="#ff0000"><?= h($errorMessage); ?></font></div>
        <p>
            <label for="title">タイトル</label><input type="text" id="title" name="title" placeholder="タイトルを入力" value="<?php if (!empty($_POST["title"])) {echo h($_POST["title"]);} ?>">
        </p>
        <p>
            <label for="name">都道府県</label>
            <select name="prefecture" id="prefecture">
                <option value=""></option>
                <option value="北海道">北海道</option>
                <option value="青森県">青森県</option>
                <option value="岩手県">岩手県</option>
                <option value="宮城県">宮城県</option>
                <option value="秋田県">秋田県</option>
                <option value="山形県">山形県</option>
                <option value="福島県">福島県</option>
                <option value="茨城県">茨城県</option>
                <option value="栃木県">栃木県</option>
                <option value="群馬県">群馬県</option>
                <option value="埼玉県">埼玉県</option>
                <option value="千葉県">千葉県</option>
                <option value="東京都">東京都</option>
                <option value="神奈川県">神奈川県</option>
                <option value="新潟県">新潟県</option>
                <option value="富山県">富山県</option>
                <option value="石川県">石川県</option>
                <option value="福井県">福井県</option>
                <option value="山梨県">山梨県</option>
                <option value="長野県">長野県</option>
                <option value="岐阜県">岐阜県</option>
                <option value="静岡県">静岡県</option>
                <option value="愛知県">愛知県</option>
                <option value="三重県">三重県</option>
                <option value="滋賀県">滋賀県</option>
                <option value="京都府">京都府</option>
                <option value="大阪府">大阪府</option>
                <option value="兵庫県">兵庫県</option>
                <option value="奈良県">奈良県</option>
                <option value="和歌山県">和歌山県</option>
                <option value="鳥取県">鳥取県</option>
                <option value="島根県">島根県</option>
                <option value="岡山県">岡山県</option>
                <option value="広島県">広島県</option>
                <option value="山口県">山口県</option>
                <option value="徳島県">徳島県</option>
                <option value="香川県">香川県</option>
                <option value="愛媛県">愛媛県</option>
                <option value="高知県">高知県</option>
                <option value="福岡県">福岡県</option>
                <option value="佐賀県">佐賀県</option>
                <option value="長崎県">長崎県</option>
                <option value="熊本県">熊本県</option>
                <option value="大分県">大分県</option>
                <option value="宮崎県">宮崎県</option>
                <option value="鹿児島県">鹿児島県</option>
                <option value="沖縄県">沖縄県</option>
            </select>
        </p>
        <p>
            <label for="region">地域</label><input type="text" id="region" name="region" placeholder="（例）渋谷" value="<?php if (!empty($_POST["region"])) {echo h($_POST["region"]);} ?>">
        </p>
        <p>
            <label for="place">場所</label><input type="text" id="place" name="place" placeholder="（例）スターバックスコーヒー渋谷文化村通り店" value="<?php if (!empty($_POST["place"])) {echo h($_POST["place"]);} ?>">
        </p>
        <p>
            <label for="カテゴリ">カテゴリ</label>
            <select name="category" id="category">
                <option value=""></option>
                <option value="ビジネス">ビジネス</option>
                <option value="お茶しばこ">お茶しばこ</option>
                <option value="美味しいもの食べたい">美味しいもの食べたい</option>
                <option value="誰かと話したい">誰かと話したい</option>
                <option value="相談したい">相談したい</option>
                <option value="出会い">出会い</option>
                
            </select>
        </p>
        <p>
            <label for="meeting_at">開催時間</label><input type="datetime-local" id="meeting_at" name="meeting_at">
        </p>
        <p>
            <label for="body">どんなミーティング？</label><textarea name="body" id="body" cols="30" rows="10"><?php if (!empty($_POST["body"])) {echo nl2br(h($_POST["body"]));} ?></textarea>
        </p>
        <input type="submit" id="create" name="create" value="ミーティングを登録" class="button">
    </fieldset>
</form>

<?php include_once "templates/footer.php"; ?>