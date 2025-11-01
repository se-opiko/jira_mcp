<template>
  <article class="article-detail">
    <header class="article-header">
      <div class="article-meta">
        <time class="article-date">{{ formatDate(article.createdAt) }}</time>
        <span v-if="article.category" class="article-category">
          {{ article.category }}
        </span>
      </div>

      <h1 class="article-title">{{ article.title }}</h1>

      <div v-if="article.tags && article.tags.length" class="article-tags">
        <NuxtLink
          v-for="tag in article.tags"
          :key="tag"
          :to="`/tags/${tag}`"
          class="tag"
        >
          {{ tag }}
        </NuxtLink>
      </div>

      <div v-if="article.thumbnail" class="article-thumbnail">
        <img :src="article.thumbnail" :alt="article.title" />
      </div>
    </header>

    <div class="article-body">
      <ArticleMarkdownRenderer :content="article.content" />
    </div>

    <footer class="article-footer">
      <div class="article-info">
        <p v-if="article.author" class="author">
          著者: {{ article.author }}
        </p>
        <p class="updated-at">
          最終更新: {{ formatDate(article.updatedAt || article.createdAt) }}
        </p>
      </div>

      <div class="article-actions">
        <NuxtLink :to="`/articles/${article.id}/edit`" class="btn btn-edit">
          編集
        </NuxtLink>
        <button @click="$emit('delete')" class="btn btn-delete">
          削除
        </button>
      </div>
    </footer>
  </article>
</template>

<script setup lang="ts">
interface Article {
  id: number | string
  title: string
  content: string
  createdAt: string
  updatedAt?: string
  thumbnail?: string
  category?: string
  tags?: string[]
  author?: string
}

defineProps<{
  article: Article
}>()

defineEmits<{
  delete: []
}>()

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.article-detail {
  background-color: #fff;
  border-radius: 0.5rem;
  overflow: hidden;
}

.article-header {
  padding: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.article-meta {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}

.article-date {
  font-size: 0.875rem;
  color: #6b7280;
}

.article-category {
  font-size: 0.875rem;
  padding: 0.375rem 0.875rem;
  background-color: #3b82f6;
  color: #fff;
  border-radius: 9999px;
  font-weight: 600;
}

.article-title {
  font-size: 2.25rem;
  font-weight: 800;
  color: #111827;
  margin: 0 0 1.5rem 0;
  line-height: 1.2;
}

.article-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tag {
  font-size: 0.875rem;
  padding: 0.375rem 0.75rem;
  background-color: #f3f4f6;
  color: #4b5563;
  text-decoration: none;
  border-radius: 9999px;
  transition: all 0.2s;
}

.tag:hover {
  background-color: #3b82f6;
  color: #fff;
}

.article-thumbnail {
  width: 100%;
  max-height: 500px;
  overflow: hidden;
  border-radius: 0.5rem;
  margin-top: 1.5rem;
}

.article-thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.article-body {
  padding: 2rem;
}

.article-footer {
  padding: 2rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.article-info {
  flex: 1;
}

.author,
.updated-at {
  font-size: 0.875rem;
  color: #6b7280;
  margin: 0.25rem 0;
}

.article-actions {
  display: flex;
  gap: 0.75rem;
}

.btn {
  padding: 0.625rem 1.25rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
  display: inline-block;
}

.btn-edit {
  background-color: #3b82f6;
  color: #fff;
}

.btn-edit:hover {
  background-color: #2563eb;
}

.btn-delete {
  background-color: #ef4444;
  color: #fff;
}

.btn-delete:hover {
  background-color: #dc2626;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .article-header {
    padding: 1.5rem;
  }

  .article-title {
    font-size: 1.75rem;
  }

  .article-body {
    padding: 1.5rem;
  }

  .article-footer {
    padding: 1.5rem;
    flex-direction: column;
    align-items: flex-start;
  }

  .article-actions {
    width: 100%;
    justify-content: stretch;
  }

  .btn {
    flex: 1;
    text-align: center;
  }
}
</style>
