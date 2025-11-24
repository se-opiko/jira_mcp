<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Nuxt 4の新機能について',
                'excerpt' => 'Nuxt 4で追加された新機能や改善点について詳しく解説します。パフォーマンスの向上やDXの改善など、注目すべきポイントをまとめました。',
                'content' => '# Nuxt 4の新機能について

Nuxt 4で追加された新機能や改善点について詳しく解説します。

## パフォーマンスの向上

Nuxt 4では、ビルド時間が大幅に短縮されました...

## DXの改善

開発体験も大きく向上しており...',
                'thumbnail' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=400&fit=crop',
                'category' => 'テクノロジー',
                'tags' => ['Nuxt.js', 'Vue.js', 'Frontend'],
                'read_time' => 5,
            ],
            [
                'title' => 'Laravel 12のベストプラクティス',
                'excerpt' => 'Laravel 12を使った開発におけるベストプラクティスを紹介します。セキュリティ、パフォーマンス、保守性の観点から推奨される実装方法を解説。',
                'content' => '# Laravel 12のベストプラクティス

Laravel 12を使った開発におけるベストプラクティスを紹介します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop',
                'category' => 'テクノロジー',
                'tags' => ['Laravel', 'PHP', 'Backend'],
                'read_time' => 8,
            ],
            [
                'title' => 'モダンなWebデザインのトレンド',
                'excerpt' => '2025年のWebデザインのトレンドを紹介します。ミニマリズム、大胆なタイポグラフィ、インタラクティブな要素など、最新のデザイン手法を解説。',
                'content' => '# モダンなWebデザインのトレンド

2025年のWebデザインのトレンドを紹介します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=400&fit=crop',
                'category' => 'デザイン',
                'tags' => ['Design', 'CSS', 'UI/UX'],
                'read_time' => 6,
            ],
            [
                'title' => 'TypeScriptで型安全なコードを書く',
                'excerpt' => 'TypeScriptを使って型安全なコードを書くためのテクニックを紹介します。ジェネリクス、ユーティリティ型、型ガードなどを活用した実践的な方法を解説。',
                'content' => '# TypeScriptで型安全なコードを書く

TypeScriptを使って型安全なコードを書くためのテクニックを紹介します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?w=800&h=400&fit=crop',
                'category' => 'テクノロジー',
                'tags' => ['TypeScript', 'JavaScript', 'Frontend'],
                'read_time' => 7,
            ],
            [
                'title' => 'レスポンシブデザインの基本',
                'excerpt' => 'レスポンシブデザインの基本概念と実装方法について解説します。メディアクエリ、フレキシブルグリッド、可変画像などの重要な要素を紹介。',
                'content' => '# レスポンシブデザインの基本

レスポンシブデザインの基本概念と実装方法について解説します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?w=800&h=400&fit=crop',
                'category' => 'デザイン',
                'tags' => ['Responsive', 'CSS', 'Mobile'],
                'read_time' => 5,
            ],
            [
                'title' => 'Gitを使った効率的なチーム開発',
                'excerpt' => 'Gitを使ったチーム開発のワークフローやベストプラクティスを紹介します。ブランチ戦略、コミットメッセージの書き方、コードレビューのコツなど。',
                'content' => '# Gitを使った効率的なチーム開発

Gitを使ったチーム開発のワークフローやベストプラクティスを紹介します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1556075798-4825dfaaf498?w=800&h=400&fit=crop',
                'category' => 'ライフスタイル',
                'tags' => ['Git', 'Development', 'Team'],
                'read_time' => 6,
            ],
            [
                'title' => 'REST APIの設計原則',
                'excerpt' => 'REST APIを設計する際の重要な原則とベストプラクティスを解説します。リソース設計、HTTPメソッドの使い分け、ステータスコードの選択など。',
                'content' => '# REST APIの設計原則

REST APIを設計する際の重要な原則とベストプラクティスを解説します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=400&fit=crop',
                'category' => 'テクノロジー',
                'tags' => ['API', 'REST', 'Backend'],
                'read_time' => 8,
            ],
            [
                'title' => 'CSSアニメーションで魅力的なUIを作る',
                'excerpt' => 'CSSアニメーションを使って魅力的なUIを作成する方法を紹介します。トランジション、キーフレーム、パフォーマンス最適化のテクニックを解説。',
                'content' => '# CSSアニメーションで魅力的なUIを作る

CSSアニメーションを使って魅力的なUIを作成する方法を紹介します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?w=800&h=400&fit=crop',
                'category' => 'デザイン',
                'tags' => ['CSS', 'Animation', 'UI'],
                'read_time' => 7,
            ],
            [
                'title' => 'コードレビューの効果的な進め方',
                'excerpt' => 'コードレビューを効果的に進めるためのテクニックとマインドセットについて解説します。建設的なフィードバックの方法や、チーム全体の品質向上につながるアプローチを紹介。',
                'content' => '# コードレビューの効果的な進め方

コードレビューを効果的に進めるためのテクニックとマインドセットについて解説します...',
                'thumbnail' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=400&fit=crop',
                'category' => 'ライフスタイル',
                'tags' => ['Code Review', 'Team', 'Quality'],
                'read_time' => 6,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
