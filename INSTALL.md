# フリマアプリ

## 環境構築

1. リポジトリをクローン

    ```bash
    git clone git@github.com:kawamata-natsuki/fleamarket-app.git
    ``` 

2. クローン後、プロジェクトディレクトリに移動してVSCodeを起動
    ```bash
    cd fleamarket-app
    code .
    ```

3. Dockerを起動する  
Docker Desktopを起動してください。  

4. プロジェクトルートにDocker用`.env` を作成する 

    ```bash
    cp .env.docker.example .env
    ```
    ※この `.env` は Docker ビルド用の設定ファイルです（ Laravelの `.env` とは別物です）。  
      以下のコマンドで自分の UID / GID を確認し、自分の環境に合わせて `.env` の UID / GID を設定してください：
      ```bash
      id -u
      id -g
      ```

5. `docker-compose.override.yml`の作成

    `docker-compose.override.yml` は、開発環境ごとの個別調整（ポート番号の変更など）を行うための設定ファイルです。  
    以下のコマンドでファイルを作成し、必要に応じて内容を編集してください：
    ```bash
    touch docker-compose.override.yml
    ```
    ```yaml
    services:
      nginx:
        ports:
          - "8090:80"  # ポートが競合する場合に各自調整
          
      phpmyadmin:
        ports:
          - 8091:80  # ポートが競合する場合に各自調整
    ```
6. 初期セットアップ  
    プロジェクトルートで以下のコマンドを実行し、初期セットアップを行います：
    ```bash
    make init
    ```
    `make init` では以下が自動で実行されます：
    - Dockerイメージのビルド
    - コンテナ起動
    - Laravel用 .env（.env.example → .env）配置
    - Composer依存インストール
    - APP_KEY生成
    - DBマイグレーション・シーディング
    - ストレージのシンボリックリンク作成

 ## メール設定
 
メール認証は Mailtrap を使用します。  
Mailtrap のアカウントを作成し、受信箱に記載される `MAIL_USERNAME` と `MAIL_PASSWORD` を `.env`設定してください：  
    ```ini
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_mailtrap_username_here
    MAIL_PASSWORD=your_mailtrap_password_here
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=no-reply@example.com
    MAIL_FROM_NAME="${APP_NAME}"  
    ```

## Stripe 設定

    Stripe に登録し、テスト用 API キーを取得して `.env` に設定します：
    ```ini
    STRIPE_KEY=your_stripe_public_key_here
    STRIPE_SECRET=your_stripe_secret_key_here
    ``` 

    ---

    補足： Stripe テストカード番号（決済テスト用）

    > 成功：4242 4242 4242 4242  
    > 失敗：4000 0000 0000 9995  
    > 有効期限：任意の未来日（例：04/34）  
    > CVC：適当な3桁（例：123）

    ---

##  権限設定

    本模擬案件では Docker 内で `appuser` を作成・使用しているため、基本的に `storage` や `bootstrap/cache` の権限変更は不要です。  
    ただし、ファイル共有設定やOS環境によっては権限エラーになる場合があります。  
    その場合は、以下のコマンドで権限を変更してください：
    ```bash
    sudo chmod -R 775 storage
    sudo chmod -R 775 bootstrap/cache
    ```

## URL(動作確認)
http://localhost:{NGINX_PORT}/login  
※ {NGINX_PORT} は `docker-compose.override.yml` で設定したポート番号です（デフォルトは8080）。


## ログイン情報一覧

※ログイン確認用のテストアカウントです。  
※管理者ユーザーは管理画面が存在しないため、ログイン確認用アカウントとしてのみ作成しています。

| ユーザー種別     | メールアドレス         | パスワード   |
|------------------|--------------------------|--------------|
| 一般ユーザー①    | mario@example.com         | 12345678     |
| 一般ユーザー②    | link@example.com          | 12345678     |
| 一般ユーザー③    | pupupu@example.com        | 12345678     |
| 管理者ユーザー   | admin@example.com         | admin1234    |


## テスト実行方法まとめ

### Featureテスト（PHPUnit）

テストケース ID11 「支払方法選択機能」は JavaScript を含むため、 Dusk による E2E テストは導入せず、 Feature テスト＋手動によるブラウザ確認で対応しています。　

1. `.env.testing.example` をコピーして `.env.testing` を作成：

   ```bash
   cp .env.testing.example .env.testing
   ```

    ※ `.env.testing.example` はテスト専用の設定テンプレートです。

2. テスト用データベースにマイグレーションを実行：

    ```
    php artisan migrate --env=testing
    ```

3. テスト実行：

    ```
    php artisan test tests/Feature
    ```

### 画像アップロードのテストについて

本模擬案件では画像アップロードのテストに  
`UploadedFile::fake()->image(...)`
を使用しています。  
そのため、 PHP の GD ライブラリが必要となりますが、 Dockerfile で既にインストール済みのため、追加対応は不要です。

