<?php
//DB接続関数（PDO）
const DB = "127.0.0.1";
const DB_ID = "root";
const DB_PW = "";
const DB_NAME = "gs_db";

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB, DB_NAME);

function getDb() {
    try {
        $pdo = new PDO('mysql:dbname=meeteaing;charset=utf8mb4;host=localhost','root','');
        return $pdo;
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。'.$e->getMessage());
    }
}