<template>
  <article class="article-card">
    <NuxtLink :to="`/articles/${article.id}`" class="card-link">
      <div v-if="article.thumbnail" class="card-image">
        <img :src="article.thumbnail" :alt="article.title" loading="lazy" />
      </div>

      <div class="card-content">
        <div class="card-meta">
          <time class="card-date">{{ formatDate(article.createdAt) }}</time>
          <span v-if="article.category" class="card-category">
            {{ article.category }}
          </span>
        </div>

        <h3 class="card-title">{{ article.title }}</h3>

        <p class="card-excerpt">{{ article.excerpt }}</p>

        <div class="card-footer">
          <div v-if="article.tags && article.tags.length" class="card-tags">
            <span
              v-for="tag in article.tags.slice(0, 3)"
              :key="tag"
              class="tag"
            >
              {{ tag }}
            </span>
          </div>

          <div class="card-read-time">
            {{ article.readTime || 5 }}分で読めます
          </div>
        </div>
      </div>
    </NuxtLink>
  </article>
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
  article: Article
}>()

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<style scoped>
.article-card {
  background-color: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
  transition: all 0.3s ease;
  height: 100%;
}

.article-card:hover {
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.card-link {
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.card-image {
  width: 100%;
  height: 200px;
  overflow: hidden;
  background-color: #f3f4f6;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.article-card:hover .card-image img {
  transform: scale(1.05);
}

.card-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  flex: 1;
}

.card-meta {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.card-date {
  font-size: 0.875rem;
  color: #6b7280;
}

.card-category {
  font-size: 0.75rem;
  padding: 0.25rem 0.625rem;
  background-color: #3b82f6;
  color: #fff;
  border-radius: 9999px;
  font-weight: 600;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-excerpt {
  font-size: 0.875rem;
  color: #6b7280;
  line-height: 1.6;
  margin: 0;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  flex: 1;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  padding-top: 0.75rem;
  border-top: 1px solid #e5e7eb;
}

.card-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.375rem;
  flex: 1;
}

.tag {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  background-color: #f3f4f6;
  color: #4b5563;
  border-radius: 0.25rem;
}

.card-read-time {
  font-size: 0.75rem;
  color: #9ca3af;
  white-space: nowrap;
}

@media (max-width: 768px) {
  .card-image {
    height: 150px;
  }

  .card-content {
    padding: 1rem;
  }

  .card-title {
    font-size: 1.125rem;
  }
}
</style>
