# ブログアプリケーション

Nuxt.js（フロントエンド）とLaravel（バックエンド）で構築されたブログアプリケーションです。

## 🚀 クイックスタート

他のPCでも簡単に開発環境を構築できます。

### 前提条件
- Docker
- Docker Compose

### 環境構築

1. **リポジトリをクローン**
```bash
git clone <your-repository-url>
cd jira_mcp_sample/jira_mcp
```

2. **コンテナを起動**
```bash
docker compose up --build
```

これだけで完了です！🎉

### アクセス先
- **フロントエンド**: http://localhost:3000
- **バックエンドAPI**: http://localhost:8000

## 📋 自動で実行される内容

### バックエンド (Laravel)
- ✅ Composer依存関係のインストール
- ✅ .envファイルの作成（存在しない場合）
- ✅ アプリケーションキーの生成
- ✅ ファイル権限の設定
- ✅ データベースマイグレーションの実行
- ✅ サンプルデータの挿入（9件のブログ記事とテストユーザー）
- ✅ キャッシュクリア

### フロントエンド (Nuxt.js)
- ✅ npm依存関係のインストール
- ✅ marked.jsライブラリの自動インストール（Markdownレンダリング用）
- ✅ 開発サーバーの起動

## 🛠️ 手動コマンド

必要に応じて個別にコマンドを実行できます：

### バックエンド
```bash
cd backend

# 依存関係のインストール
composer install

# マイグレーション実行
php artisan migrate

# サンプルデータの挿入
php artisan db:seed

# 開発サーバー起動
php artisan serve --host=0.0.0.0 --port=8000
```

### フロントエンド
```bash
cd frontend

# 依存関係のインストール
npm install

# 開発サーバー起動
npm run dev
```

## 🏗️ アーキテクチャ

- **フロントエンド**: Nuxt 4 + Vue 3 + marked.js
- **バックエンド**: Laravel 12 + SQLite
- **コンテナ**: Docker + Docker Compose

## 📝 初期データ

サンプルデータとして以下が自動で作成されます：

**ユーザー**
- Email: test@example.com
- Name: Test User

**記事**
- 9件のサンプル記事（技術、デザイン、ライフスタイル各種カテゴリ）

## 🔄 再構築

環境をクリーンな状態から再構築したい場合：

```bash
# コンテナと関連データを削除
docker compose down -v

# 再ビルドして起動
docker compose up --build
```

## 🐛 トラブルシューティング

### ポート競合
もし3000番や8000番ポートが使用中の場合、docker-compose.ymlの`ports`セクションを編集してください。

### 依存関係エラー
コンテナを完全に再ビルドしてください：
```bash
docker compose down
docker compose build --no-cache
docker compose up
```

## 📱 機能

- Markdownでのブログ記事作成
- 記事の一覧表示
- 記事の詳細表示
- リアルタイムMarkdownプレビュー
- レスポンシブデザイン
- カテゴリ・タグ機能
