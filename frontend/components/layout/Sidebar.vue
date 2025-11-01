<template>
  <aside class="sidebar">
    <section class="sidebar-section">
      <h3 class="sidebar-title">カテゴリ</h3>
      <ul class="category-list">
        <li v-for="category in categories" :key="category.name" class="category-item">
          <NuxtLink :to="`/articles?category=${category.name}`" class="category-link">
            {{ category.name }}
            <span class="category-count">{{ category.count }}</span>
          </NuxtLink>
        </li>
      </ul>
    </section>

    <section class="sidebar-section">
      <h3 class="sidebar-title">最近の記事</h3>
      <ul class="recent-posts">
        <li v-for="post in recentPosts" :key="post.id" class="recent-post-item">
          <NuxtLink :to="`/articles/${post.id}`" class="recent-post-link">
            <h4 class="recent-post-title">{{ post.title }}</h4>
            <time class="recent-post-date">{{ formatDate(post.created_at) }}</time>
          </NuxtLink>
        </li>
      </ul>
    </section>

    <section class="sidebar-section">
      <h3 class="sidebar-title">タグ</h3>
      <div class="tag-cloud">
        <span
          v-for="tag in tags"
          :key="tag.name"
          class="tag"
        >
          {{ tag.name }}
        </span>
      </div>
    </section>
  </aside>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Category {
  name: string
  count: number
}

interface Tag {
  name: string
  count: number
}

interface Article {
  id: number
  title: string
  created_at: string
}

const categories = ref<Category[]>([])
const tags = ref<Tag[]>([])
const recentPosts = ref<Article[]>([])

// カテゴリーを取得
const fetchCategories = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/categories')
    if (response.ok) {
      categories.value = await response.json()
    }
  } catch (error) {
    console.error('カテゴリーの取得に失敗しました:', error)
  }
}

// タグを取得
const fetchTags = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/tags')
    if (response.ok) {
      tags.value = await response.json()
    }
  } catch (error) {
    console.error('タグの取得に失敗しました:', error)
  }
}

// 最近の記事を取得
const fetchRecentPosts = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/articles?per_page=4')
    if (response.ok) {
      const data = await response.json()
      recentPosts.value = data.data.slice(0, 4)
    }
  } catch (error) {
    console.error('最近の記事の取得に失敗しました:', error)
  }
}

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr)
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(() => {
  fetchCategories()
  fetchTags()
  fetchRecentPosts()
})
</script>

<style scoped>
.sidebar {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.sidebar-section {
  background-color: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  padding: 1.5rem;
}

.sidebar-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 1rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #3b82f6;
}

.category-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.category-item {
  margin: 0;
}

.category-link {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  color: #4b5563;
  text-decoration: none;
  border-radius: 0.25rem;
  transition: all 0.2s;
}

.category-link:hover {
  background-color: #f3f4f6;
  color: #3b82f6;
}

.category-count {
  font-size: 0.875rem;
  color: #9ca3af;
  background-color: #f3f4f6;
  padding: 0.125rem 0.5rem;
  border-radius: 0.25rem;
}

.recent-posts {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.recent-post-item {
  margin: 0;
  padding-bottom: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.recent-post-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.recent-post-link {
  text-decoration: none;
  color: inherit;
}

.recent-post-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0 0 0.25rem 0;
  line-height: 1.4;
  transition: color 0.2s;
}

.recent-post-link:hover .recent-post-title {
  color: #3b82f6;
}

.recent-post-date {
  font-size: 0.75rem;
  color: #9ca3af;
}

.tag-cloud {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tag {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  background-color: #f3f4f6;
  color: #4b5563;
  text-decoration: none;
  border-radius: 9999px;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.tag:hover {
  background-color: #3b82f6;
  color: #fff;
}

/* タブレット・モバイル対応 */
@media (max-width: 1024px) {
  .sidebar {
    margin-top: 2rem;
  }
}

@media (max-width: 768px) {
  .sidebar-section {
    padding: 1rem;
  }
}
</style>
