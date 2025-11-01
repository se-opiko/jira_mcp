<template>
  <div class="home-page">
    <section class="hero">
      <h1 class="hero-title">ようこそブログアプリへ</h1>
      <p class="hero-description">
        Nuxt.js + Laravel で構築されたモダンなブログシステム
      </p>
      <div class="hero-actions">
        <NuxtLink to="/articles" class="btn btn-primary">
          記事を読む
        </NuxtLink>
        <NuxtLink to="/articles/create" class="btn btn-secondary">
          記事を書く
        </NuxtLink>
      </div>
    </section>

    <section class="featured-section">
      <h2 class="section-title">最新の記事</h2>
      <ArticleList :articles="sampleArticles" />
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

// サンプルデータ（後でAPIから取得する）
const sampleArticles = ref([
  {
    id: 1,
    title: 'Nuxt 4の新機能について',
    excerpt: 'Nuxt 4で追加された新機能や改善点について詳しく解説します。パフォーマンスの向上やDXの改善など、注目すべきポイントをまとめました。',
    createdAt: '2025-10-15',
    category: 'テクノロジー',
    tags: ['Nuxt.js', 'Vue.js', 'Frontend'],
    readTime: 5,
    thumbnail: 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=400&fit=crop'
  },
  {
    id: 2,
    title: 'Laravel 12のベストプラクティス',
    excerpt: 'Laravel 12を使った開発におけるベストプラクティスを紹介します。セキュリティ、パフォーマンス、保守性の観点から推奨される実装方法を解説。',
    createdAt: '2025-10-14',
    category: 'テクノロジー',
    tags: ['Laravel', 'PHP', 'Backend'],
    readTime: 8,
    thumbnail: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop'
  },
  {
    id: 3,
    title: 'モダンなWebデザインのトレンド',
    excerpt: '2025年のWebデザインのトレンドを紹介します。ミニマリズム、大胆なタイポグラフィ、インタラクティブな要素など、最新のデザイン手法を解説。',
    createdAt: '2025-10-13',
    category: 'ライフスタイル',
    tags: ['Design', 'CSS', 'UI/UX'],
    readTime: 6,
    thumbnail: 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=400&fit=crop'
  }
])
</script>

<style scoped>
.home-page {
  width: 100%;
}

.hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #fff;
  padding: 4rem 2rem;
  text-align: center;
  border-radius: 0.5rem;
  margin-bottom: 3rem;
}

.hero-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 1rem;
  line-height: 1.2;
}

.hero-description {
  font-size: 1.25rem;
  margin-bottom: 2rem;
  opacity: 0.95;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.hero-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn {
  padding: 0.875rem 2rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-block;
}

.btn-primary {
  background-color: #fff;
  color: #667eea;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.btn-secondary {
  background-color: rgba(255, 255, 255, 0.2);
  color: #fff;
  border: 2px solid #fff;
}

.btn-secondary:hover {
  background-color: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

.featured-section {
  margin-bottom: 3rem;
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 2rem;
  color: #1f2937;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .hero {
    padding: 3rem 1.5rem;
  }

  .hero-title {
    font-size: 2rem;
  }

  .hero-description {
    font-size: 1.125rem;
  }

  .hero-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .btn {
    width: 100%;
    text-align: center;
  }

  .section-title {
    font-size: 1.5rem;
  }
}
</style>
