<?php 
session_start(); // セッションを開始
include_once "config/database.php"; // db情報取得
?> 

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MEETEAING</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<header>
    <div class="f_left">
        <p class="logo"><a href="index.php">MEETEAING</a></p>
    </div> 
    <div class="f_right">
        <?php if(isset($_SESSION["ID"])) : ?>
        <p class="avatar" style="background-image:url(./uploads/<?=$_SESSION["AVATAR"]?>)"></p>
        <p class="search"><a href="create.php">ミーティングを追加</a></p>
        <?php else : ?>
        <p class="signup"><a href="login.php">ログイン</a></p>
        <?php endif; ?>
    </div>
</header>
<ul class="menu_modal">
    <li><a href="mypage.php">プロフィール設定</a></li>
    <li id="logout"><a href="logout.php">ログアウト</a></li>
</ul>
<main>