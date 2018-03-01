<?php

include_once "templates/header.php";

// 以下DBからmeetingsテーブルのレコード取得
try { 
    $pdo = getDb();
    // ミーティング全件取得するステートメントを用意
    $pre_query = 'SELECT * FROM meetings ORDER BY created_at';
    $stmt = $pdo->prepare($pre_query);
    // ステートメントを実行
    $stmt->execute();
    // 抽出が成功した時の処理
    $meetings = [];
    $count = 0;
    foreach($stmt->fetchAll() as $row) { 
        $view = "";
        // カラムを変数に代入
        $id = $row["id"];
        $user_id = $row["user_id"];
        $title = $row["title"];
        $body = $row["body"];
        $prefecture = $row["prefecture"];
        $region = $row["region"];
        $place = $row["place"];
        $category = $row["category"];
        $meeting_at = $row["meeting_at"];
        $status = $row["status"] ? '成立' : '募集中';

        // ユーザーデータを取得するステートメントを用意
        $pre_query_u = 'SELECT * FROM users WHERE id = '.$user_id;
        $stmt_u = $pdo->prepare($pre_query_u);
        // ステートメントを実行
        $stmt_u->execute();
        $fetch_data = $stmt_u->fetch();
        $avatar = $fetch_data["avatar"];
        $name = $fetch_data["name"];
        $profile = $fetch_data["profile"];

        // レコード単位で情報をhtml化
        $push_array = '<li id="'.$id.'">';
        $push_array .= '<p><a href="profile.php?id='.$user_id.'"><p class="user_avatar" style="background-image:url(./uploads/'.$avatar.')"></p></a><br>'.$name.'<br>'.$profile.'</p>';
        $push_array .= $user_id.'<br>'.$title.'<br>'.$body.'<br>'.$prefecture.'<br>'.$region.'<br>'.$place.'<br>'.$category.'<br>'.$meeting_at.'<br>';
        $push_array .= '<a href="single.php?id='.$id.'"><button class="button">'.$status.'</button></a>';
        $push_array .= '</li>';
        array_push($meetings, $push_array);
        $count++;
    }
} catch (PDOException $e) {
    $errorMessage = "DBエラー";
    var_dump($e->getMessage());
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<?php if (isset($_SESSION["NAME"])) { //ログインしてたら ?>
<p>ようこそ<u><?= h($_SESSION["NAME"]); ?></u>さん</p>
<?php } else { //してなかったら ?>
<ul>
    <li id="search_btn"><a href="create.php">ユーザー登録する</a></li>
</ul>
<?php } ?>
<ul id="meetings_list">
<?php 
foreach($meetings as $meeting) {
    print $meeting;
}
?>
</ul>


<?php include_once "templates/footer.php"; ?>

