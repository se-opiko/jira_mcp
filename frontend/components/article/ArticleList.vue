<template>
  <div class="article-list">
    <div v-if="loading" class="loading">
      <p>読み込み中...</p>
    </div>

    <div v-else-if="articles.length === 0" class="empty">
      <p>記事がありません</p>
    </div>

    <div v-else class="articles-grid">
      <ArticleCard
        v-for="article in articles"
        :key="article.id"
        :article="article"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
interface Article {
  id: number | string
  title: string
  excerpt: string
  createdAt: string
  thumbnail?: string
  category?: string
  tags?: string[]
  readTime?: number
}

defineProps<{
  articles: Article[]
  loading?: boolean
}>()
</script>

<style scoped>
.article-list {
  width: 100%;
}

.articles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
}

.loading,
.empty {
  text-align: center;
  padding: 3rem 1rem;
  color: #6b7280;
}

.loading p,
.empty p {
  margin: 0;
  font-size: 1rem;
}

/* タブレット対応 */
@media (max-width: 1024px) {
  .articles-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
  }
}

/* モバイル対応 */
@media (max-width: 768px) {
  .articles-grid {
    grid-template-columns: 1fr;
    gap: 1.25rem;
  }
}
</style>
