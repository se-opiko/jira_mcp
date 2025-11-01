<template>
  <div class="articles-page">
    <div class="page-header">
      <h1 class="page-title">記事一覧</h1>
      <p class="page-description">
        ブログの記事をご覧いただけます
      </p>
    </div>

    <div class="filters-section">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="記事を検索..."
          class="search-input"
          @input="handleSearch"
        />
      </div>

      <div class="category-filters">
        <button
          :class="['filter-btn', { active: selectedCategory === null }]"
          @click="selectCategory(null)"
        >
          全て
        </button>
        <button
          v-for="category in categories"
          :key="category"
          :class="['filter-btn', { active: selectedCategory === category }]"
          @click="selectCategory(category)"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <ArticleList :articles="filteredArticles" :loading="loading" />

    <Pagination
      v-if="totalPages > 1"
      :current-page="currentPage"
      :total-pages="totalPages"
      @page-change="handlePageChange"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

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

const route = useRoute()
const router = useRouter()
const loading = ref(false)
const articles = ref<Article[]>([])
const searchQuery = ref('')
const selectedCategory = ref<string | null>(null)
const currentPage = ref(1)
const itemsPerPage = 9

// カテゴリーのリストを取得
const categories = computed(() => {
  const cats = new Set<string>()
  articles.value.forEach(article => {
    if (article.category) {
      cats.add(article.category)
    }
  })
  return Array.from(cats)
})

// フィルタリングされた記事
const filteredArticles = computed(() => {
  let filtered = articles.value

  // カテゴリーフィルター
  if (selectedCategory.value) {
    filtered = filtered.filter(
      article => article.category === selectedCategory.value
    )
  }

  // 検索フィルター
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(
      article =>
        article.title.toLowerCase().includes(query) ||
        article.excerpt.toLowerCase().includes(query) ||
        article.tags?.some(tag => tag.toLowerCase().includes(query))
    )
  }

  // ページネーション
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filtered.slice(start, end)
})

// 総ページ数
const totalPages = computed(() => {
  let filtered = articles.value

  if (selectedCategory.value) {
    filtered = filtered.filter(
      article => article.category === selectedCategory.value
    )
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(
      article =>
        article.title.toLowerCase().includes(query) ||
        article.excerpt.toLowerCase().includes(query) ||
        article.tags?.some(tag => tag.toLowerCase().includes(query))
    )
  }

  return Math.ceil(filtered.length / itemsPerPage)
})

const selectCategory = (category: string | null) => {
  selectedCategory.value = category
  currentPage.value = 1
}

const handleSearch = () => {
  currentPage.value = 1
}

const handlePageChange = (page: number) => {
  currentPage.value = page
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// 記事データの取得
const fetchArticles = async () => {
  loading.value = true
  try {
    const response = await fetch('http://localhost:8000/api/articles')

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()

    // Laravelのページネーションレスポンスから記事データを取得
    // データ構造を確認し、適切に変換
    articles.value = data.data.map((article: any) => ({
      id: article.id,
      title: article.title,
      excerpt: article.excerpt,
      createdAt: article.created_at,
      thumbnail: article.thumbnail,
      category: article.category,
      tags: article.tags,
      readTime: article.read_time
    }))
  } catch (error) {
    console.error('記事の取得に失敗しました:', error)
    // エラー時は空配列を設定
    articles.value = []
  } finally {
    loading.value = false
  }
}

// URLクエリパラメータからカテゴリを読み取る
const initializeCategoryFromQuery = () => {
  const categoryParam = route.query.category as string | undefined
  if (categoryParam) {
    selectedCategory.value = categoryParam
  }
}

// URLクエリパラメータの変更を監視
watch(() => route.query.category, (newCategory) => {
  selectedCategory.value = newCategory as string | null || null
  currentPage.value = 1
})

onMounted(() => {
  initializeCategoryFromQuery()
  fetchArticles()
})

// SEO対策
useHead({
  title: '記事一覧 - ブログアプリ',
  meta: [
    {
      name: 'description',
      content: 'テクノロジー、デザイン、ライフスタイルに関する記事をご覧いただけます。'
    }
  ]
})
</script>

<style scoped>
.articles-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.page-header {
  text-align: center;
  margin-bottom: 3rem;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.page-description {
  font-size: 1.125rem;
  color: #6b7280;
  margin: 0;
}

.filters-section {
  margin-bottom: 2rem;
}

.search-box {
  margin-bottom: 1.5rem;
}

.search-input {
  width: 100%;
  padding: 0.875rem 1rem;
  font-size: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  outline: none;
  transition: border-color 0.2s;
}

.search-input:focus {
  border-color: #667eea;
}

.category-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.filter-btn {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 600;
  border: 2px solid #e5e7eb;
  background-color: #fff;
  color: #6b7280;
  border-radius: 9999px;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-btn:hover {
  border-color: #667eea;
  color: #667eea;
}

.filter-btn.active {
  background-color: #667eea;
  border-color: #667eea;
  color: #fff;
}

@media (max-width: 768px) {
  .articles-page {
    padding: 1.5rem 1rem;
  }

  .page-header {
    margin-bottom: 2rem;
  }

  .page-title {
    font-size: 2rem;
  }

  .page-description {
    font-size: 1rem;
  }

  .category-filters {
    gap: 0.5rem;
  }

  .filter-btn {
    padding: 0.375rem 0.875rem;
    font-size: 0.8125rem;
  }
}
</style>
