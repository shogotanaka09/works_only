# WordPress案件開発テンプレート

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->

- [前提条件](#%E5%89%8D%E6%8F%90%E6%9D%A1%E4%BB%B6)
- [構成](#%E6%A7%8B%E6%88%90)
  - [インフラ](#%E3%82%A4%E3%83%B3%E3%83%95%E3%83%A9)
    - [Dockerコンテナ](#docker%E3%82%B3%E3%83%B3%E3%83%86%E3%83%8A)
  - [フロントエンド](#%E3%83%95%E3%83%AD%E3%83%B3%E3%83%88%E3%82%A8%E3%83%B3%E3%83%89)
- [開発環境構築手順](#%E9%96%8B%E7%99%BA%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86)
- [ステージング環境構築手順](#%E3%82%B9%E3%83%86%E3%83%BC%E3%82%B8%E3%83%B3%E3%82%B0%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86)
- [本番およびステージング環境へのデータ同期手順](#%E6%9C%AC%E7%95%AA%E3%81%8A%E3%82%88%E3%81%B3%E3%82%B9%E3%83%86%E3%83%BC%E3%82%B8%E3%83%B3%E3%82%B0%E7%92%B0%E5%A2%83%E3%81%B8%E3%81%AE%E3%83%87%E3%83%BC%E3%82%BF%E5%90%8C%E6%9C%9F%E6%89%8B%E9%A0%86)
- [更新作業時における基本的な同期順序](#%E6%9B%B4%E6%96%B0%E4%BD%9C%E6%A5%AD%E6%99%82%E3%81%AB%E3%81%8A%E3%81%91%E3%82%8B%E5%9F%BA%E6%9C%AC%E7%9A%84%E3%81%AA%E5%90%8C%E6%9C%9F%E9%A0%86%E5%BA%8F)
- [環境変数](#%E7%92%B0%E5%A2%83%E5%A4%89%E6%95%B0)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## 前提条件
- Dockerがインストールされている
- docker-composeがインストールされている
- Node.jsがインストールされている

## 構成
### インフラ
#### Dockerコンテナ
- WordPress用データベース
- WordPress本体兼Webサーバー
- WP CLI
- WordMove（デプロイ用）

### フロントエンド
- Webpack
- Dart Sass & PostCSS

## 開発環境構築手順（リポジトリオーナー）
1. `npm install`を実行する
2. `npm run parepare`を実行する
3. `npm run setup`を実行し、プロンプトの内容に従って必要な値を入力する
4. `docker compose up -d`を実行する
5. wordpressコンテナの起動が完了したことを確認する（`wp-config.php`が生成済みであれば基本的にはOK
6. `docker compose run --rm cli bash`を実行し、WP CLI用コンテナに入る
7. (WP CLIコンテナ内)`pwd`コマンドでカレントディレクトリが`/var/www/html`であることを確認し、`sh /tmp/wp-install.sh`を実行する
8. `http://localhost:{LOCAL_SERVER_PORT}`に接続し、コンテナが正常に構築されていることを確認する

## 開発環境構築手順（一般開発者）
1. `npm install`を実行する
2. `npm run parepare`を実行する
3. リポジトリオーナーから`.env`を共有してもらう
4. リポジトリオーナーからステージングサーバー接続用の秘密鍵を共有してもらう
5. 必要に応じ、`.env`の`LOCAL_SERVER_PORT` `LOCAL_DB_PORT` `ADMIN_EMAIL`値を書き換える
6. 4で入手した秘密鍵を`.ssh-keys`ディレクトリに追加する
7. `docker compose up -d`を実行する
8. wordpressコンテナの起動が完了したことを確認する（`wp-config.php`が生成済みであれば基本的にはOK
9. `docker-compose run --rm wordmove bash`を行い、`WordMove`用コンテナに入る
10. `# ssh-agent bash` → `ssh-add .ssh/*`で秘密鍵を登録する
11. `# wordmove pull --all -e staging`でステージングサーバーから全てのデータを同期する

## ステージング環境構築手順
1. root権限を持つユーザーでインスタンスにSSH接続を行う
2. `/home/{ユーザー名}/projects`以下に案件のディレクトリを作成
3. GitHubから案件のリポジトリをクローン
4. 開発環境の`.env`をステージング環境にコピー
5. `LOCAL_DB_PORT`や`LOCAL_SERVER_PORT`などを必要に応じて変更
6. 開発環境と同じ手順でコンテナを構築
7. BASIC認証を構築
8. 開発環境からwordmoveを使ってステージング環境に各データを同期

## 本番およびステージング環境へのデータ同期手順
1. `docker compose run --rm wordmove bash`でwordmoveコンテナに入る
2. `ssh-agent bash` `ssh-add .ssh/*`でSSH接続用の秘密鍵を登録する
3. `wordmove push --all -e {対象の環境}`ですべてのWP構成ファイルをリモートに動悸する

## 更新作業時における基本的な同期順序
1. 本番環境からすべてのデータを開発環境に同期
2. 開発環境からすべてのデータをステージング環境に同期
3. ステージング環境で確認作業を行う
4. 開発環境からすべてのデータを本番環境に同期

## 環境変数
|変数名|説明|例|
|:--|:--|:--|
|APP_NAME|アプリケーション固有の名前|sample-project|
|LOCAL_DB_PORT|ホスト側で用いるデータベースのポート|10001|
|LOCAL_SERVER_PORT|ホスト側で用いるWebサーバーのポート|10002|
|ADMIN_EMAIL|wordpressの管理者として登録するメールアドレス|sample-project@curious-inc.jp|
|WP_DIR|wordpress本体がインストールされる`public`以下のディレクトリ|wordpress|
|THEME_NAME|テーマ名|sample-project|
|STAGING_URL|ステージングサーバーのURL|http://sample-project.curious-staging.jp|
|STAGING_WP_DIR|ステージングサーバー上のwordpress本体がインストールされるディレクトリ|/home/sample-project/public/wordpress|
|STAGING_DB_NAME|ステージング用データベース名|wordpress|
|STAGING_DB_USER|ステージング用データベースのユーザー名|wordpress|
|STAGING_DB_PASSWORD|ステージング用データベースのパスワード|wordpress|
|STAGING_DB_HOST|ステージングサーバー用データベースのホスト名|xxx.xxx.xxx.xxx|
|STAGING_DB_PORT|ステージングサーバー用データベースのポート番号|10001|
|STAGING_SSH_HOST|ステージングサーバーにSSH接続する際のホスト名|xxx.xxx.xxx.xxx|
|STAGING_SSH_PORT|ステージングサーバーにSSH接続する際のポート番号|22|
|STAGING_SSH_USER|ステージングサーバーにSSH接続する際のユーザー名|sample-project|
|PRODUCTION_URL|本番サーバーのURL|http://sample-project.jp|
|PRODUCTION_WP_DIR|本番サーバー上のwordpress本体がインストールされるディレクトリ|/home/sample-project/public/wordpress|
|PRODUCTION_DB_NAME|本番用データベース名|wordpress|
|PRODUCTION_DB_USER|本番用データベースのユーザー名|wordpress|
|PRODUCTION_DB_PASSWORD|本番用データベースのパスワード|wordpress|
|PRODUCTION_DB_HOST|本番サーバー用データベースのホスト名|xxx.xxx.xxx.xxx|
|PRODUCTION_DB_PORT|本番サーバー用データベースのポート番号|10001|
|PRODUCTION_SSH_HOST|本番サーバーにSSH接続する際のホスト名|xxx.xxx.xxx.xxx|
|PRODUCTION_SSH_PORT|本番サーバーにSSH接続する際のポート番号|22|
|PRODUCTION_SSH_USER|本番サーバーにSSH接続する際のユーザー名|sample-project|
