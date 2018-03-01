# Gs DEV9 php09（LOGIN）課題

## 使用した技術要素
- XAMPP 5.6.3
- PHP 5.6.3
- Apache 2.4.10
- MySQL 5.6.21
- phpMyAdmin 4.2.11

## ディレクトリ構成
  ```
  .
  ├── assets
    └── css .. スタイルシート
  ├── tempaltes/ .. テンプレートフォルダ
    ├── header.php .. ヘッダー
    └── footer.php .. フッター
  ├── config/ .. 設定フォルダ
    └── database.php .. DB情報（環境に合わせて書き換えてください）
  ├── uploads/ .. アップロード画像フォルダ
  ├── index.php .. トップ
  ├── signup.php .. 新規登録
  ├── login.php .. ログイン
  ├── logout.php .. ログアウト
  ├── mypage.php .. マイページ
  ├── create.php .. ミーティング作成
  ├── message_save.php .. メッセージを保存する
  ├── profile.php .. 他人のプロフィールページ
  ├── single.php .. ミーティングの詳細ページ
  └── password.php .. パスワードのハッシュ化補助

  ```

## DB構成
dumpファイル参照（messages.sql)