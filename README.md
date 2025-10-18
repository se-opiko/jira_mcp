# ブログアプリケーション

Nuxt.js (フロントエンド) + Laravel (バックエンド) で構築されたブログシステム

## プロジェクト構成

```
jira_mcp/
├── frontend/          # Nuxt.js フロントエンド
├── backend/           # Laravel バックエンド
└── README.md
```

## 技術スタック

### フロントエンド
- **Nuxt.js** 4.x
- **Vue.js** 3.x
- **marked.js** - Markdown変換ライブラリ

### バックエンド
- **Laravel** 12.x
- **PHP** 8.2+
- **SQLite** (開発環境)

## セットアップ

### Docker環境での起動 (推奨)

#### 前提条件
- Docker
- Docker Compose

#### 起動方法

```bash
docker-compose up --build
```

サーバーが起動します:
- フロントエンド: http://localhost:3000
- バックエンド: http://localhost:8000

#### 停止方法

```bash
docker-compose down
```

### ローカル環境での起動

#### 前提条件
- Node.js 18.x 以上
- PHP 8.2 以上
- Composer

#### フロントエンド

```bash
cd frontend
npm install
npm run dev
```

開発サーバーが起動します: http://localhost:3000

#### バックエンド

```bash
cd backend
composer install
php artisan serve
```

APIサーバーが起動します: http://localhost:8000

## 開発

### フロントエンド (Nuxt.js)

```bash
npm run dev      # 開発サーバー起動
npm run build    # 本番用ビルド
npm run preview  # ビルド結果のプレビュー
```

### バックエンド (Laravel)

```bash
php artisan serve           # 開発サーバー起動
php artisan migrate         # マイグレーション実行
php artisan make:controller # コントローラー作成
```

## 機能

- Markdownでのブログ記事作成
- 記事の一覧表示
- 記事の詳細表示
- レスポンシブデザイン

## ライセンス

MIT
