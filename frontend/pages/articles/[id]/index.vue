<template>
  <div class="article-detail-page">
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>記事を読み込み中...</p>
    </div>

    <div v-else-if="error" class="error-container">
      <h2>エラーが発生しました</h2>
      <p>{{ error }}</p>
      <NuxtLink to="/articles" class="back-link">記事一覧に戻る</NuxtLink>
    </div>

    <article v-else-if="article" class="article-content">
      <header class="article-header">
        <div class="breadcrumb">
          <NuxtLink to="/" class="breadcrumb-link">ホーム</NuxtLink>
          <span class="breadcrumb-separator">/</span>
          <NuxtLink to="/articles" class="breadcrumb-link">記事一覧</NuxtLink>
          <span class="breadcrumb-separator">/</span>
          <span class="breadcrumb-current">{{ article.title }}</span>
        </div>

        <h1 class="article-title">{{ article.title }}</h1>

        <div class="article-meta">
          <span v-if="article.category" class="category-badge">
            {{ article.category }}
          </span>
          <time class="article-date" :datetime="article.createdAt">
            {{ formatDate(article.createdAt) }}
          </time>
          <span v-if="article.readTime" class="read-time">
            読了時間: {{ article.readTime }}分
          </span>
        </div>

        <div v-if="article.tags && article.tags.length > 0" class="article-tags">
          <span v-for="tag in article.tags" :key="tag" class="tag">
            #{{ tag }}
          </span>
        </div>

        <img
          v-if="article.thumbnail"
          :src="article.thumbnail"
          :alt="article.title"
          class="article-thumbnail"
        />
      </header>

      <div class="article-body" v-html="renderedContent"></div>

      <footer class="article-footer">
        <div class="footer-actions">
          <NuxtLink to="/articles" class="back-button">
            ← 記事一覧に戻る
          </NuxtLink>
          <div class="action-buttons">
            <NuxtLink :to="`/articles/${article.id}/edit`" class="edit-button">
              編集
            </NuxtLink>
            <button @click="showDeleteDialog = true" class="delete-button">
              削除
            </button>
          </div>
        </div>
      </footer>
    </article>

    <!-- 削除確認ダイアログ -->
    <div v-if="showDeleteDialog" class="dialog-overlay" @click="showDeleteDialog = false">
      <div class="dialog-content" @click.stop>
        <h2 class="dialog-title">記事を削除しますか?</h2>
        <p class="dialog-message">
          この操作は取り消せません。本当に削除しますか？
        </p>
        <div class="dialog-actions">
          <button @click="showDeleteDialog = false" class="btn btn-cancel">
            キャンセル
          </button>
          <button @click="handleDelete" class="btn btn-delete" :disabled="isDeleting">
            {{ isDeleting ? '削除中...' : '削除する' }}
          </button>
        </div>
        <p v-if="deleteError" class="error-message">{{ deleteError }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { marked } from 'marked'

interface Article {
  id: number | string
  title: string
  excerpt: string
  content: string
  createdAt: string
  updatedAt: string
  thumbnail?: string
  category?: string
  tags?: string[]
  readTime?: number
}

const route = useRoute()
const router = useRouter()
const article = ref<Article | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const showDeleteDialog = ref(false)
const isDeleting = ref(false)
const deleteError = ref('')

// Markdownをレンダリング
const renderedContent = computed(() => {
  if (!article.value?.content) return ''
  return marked(article.value.content)
})

// 日付のフォーマット
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// 記事の取得
const fetchArticle = async () => {
  loading.value = true
  error.value = null

  try {
    const id = route.params.id
    const response = await fetch(`http://localhost:8000/api/articles/${id}`)

    if (!response.ok) {
      if (response.status === 404) {
        throw new Error('記事が見つかりませんでした')
      }
      throw new Error(`記事の取得に失敗しました (ステータス: ${response.status})`)
    }

    const data = await response.json()

    article.value = {
      id: data.id,
      title: data.title,
      excerpt: data.excerpt,
      content: data.content,
      createdAt: data.created_at,
      updatedAt: data.updated_at,
      thumbnail: data.thumbnail,
      category: data.category,
      tags: data.tags,
      readTime: data.read_time
    }
  } catch (err) {
    error.value = err instanceof Error ? err.message : '記事の取得に失敗しました'
    console.error('記事の取得エラー:', err)
  } finally {
    loading.value = false
  }
}

// 記事の削除
const handleDelete = async () => {
  if (!article.value) return

  isDeleting.value = true
  deleteError.value = ''

  try {
    const response = await fetch(`http://localhost:8000/api/articles/${article.value.id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json'
      }
    })

    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.message || '記事の削除に失敗しました')
    }

    // 削除成功後、記事一覧ページへリダイレクト
    router.push('/articles')
  } catch (err) {
    deleteError.value = err instanceof Error ? err.message : '記事の削除に失敗しました'
    console.error('記事削除エラー:', err)
  } finally {
    isDeleting.value = false
  }
}

onMounted(() => {
  fetchArticle()
})

// SEO対策
useHead({
  title: computed(() => article.value ? `${article.value.title} - ブログアプリ` : '読み込み中... - ブログアプリ'),
  meta: computed(() => [
    {
      name: 'description',
      content: article.value?.excerpt || '記事を読み込んでいます'
    }
  ])
})
</script>

<style scoped>
.article-detail-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.loading-container,
.error-container {
  text-align: center;
  padding: 4rem 2rem;
}

.loading-spinner {
  border: 4px solid #f3f4f6;
  border-top: 4px solid #667eea;
  border-radius: 50%;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-container h2 {
  color: #dc2626;
  margin-bottom: 1rem;
}

.back-link {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background-color: #667eea;
  color: #fff;
  text-decoration: none;
  border-radius: 0.375rem;
  transition: background-color 0.2s;
}

.back-link:hover {
  background-color: #5568d3;
}

.article-content {
  background-color: #fff;
  border-radius: 0.5rem;
  overflow: hidden;
}

.article-header {
  padding: 2rem;
  border-bottom: 1px solid #e5e7eb;
}

.breadcrumb {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 1.5rem;
}

.breadcrumb-link {
  color: #667eea;
  text-decoration: none;
  transition: color 0.2s;
}

.breadcrumb-link:hover {
  color: #5568d3;
}

.breadcrumb-separator {
  margin: 0 0.5rem;
}

.breadcrumb-current {
  color: #1f2937;
  font-weight: 500;
}

.article-title {
  font-size: 2.25rem;
  font-weight: 800;
  color: #1f2937;
  line-height: 1.3;
  margin-bottom: 1rem;
}

.article-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  margin-bottom: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}

.category-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background-color: #667eea;
  color: #fff;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.8125rem;
}

.article-date {
  display: flex;
  align-items: center;
}

.read-time {
  display: flex;
  align-items: center;
}

.article-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tag {
  padding: 0.25rem 0.75rem;
  background-color: #f3f4f6;
  color: #4b5563;
  border-radius: 0.375rem;
  font-size: 0.8125rem;
  font-weight: 500;
}

.article-thumbnail {
  width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin-top: 1.5rem;
  object-fit: cover;
  max-height: 400px;
}

.article-body {
  padding: 2rem;
  line-height: 1.8;
  font-size: 1.0625rem;
  color: #374151;
}

.article-body :deep(h1),
.article-body :deep(h2),
.article-body :deep(h3),
.article-body :deep(h4),
.article-body :deep(h5),
.article-body :deep(h6) {
  font-weight: 700;
  color: #1f2937;
  margin-top: 2rem;
  margin-bottom: 1rem;
  line-height: 1.3;
}

.article-body :deep(h2) {
  font-size: 1.875rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #e5e7eb;
}

.article-body :deep(h3) {
  font-size: 1.5rem;
}

.article-body :deep(p) {
  margin-bottom: 1.25rem;
}

.article-body :deep(a) {
  color: #667eea;
  text-decoration: underline;
  transition: color 0.2s;
}

.article-body :deep(a:hover) {
  color: #5568d3;
}

.article-body :deep(ul),
.article-body :deep(ol) {
  margin-bottom: 1.25rem;
  padding-left: 2rem;
}

.article-body :deep(li) {
  margin-bottom: 0.5rem;
}

.article-body :deep(code) {
  background-color: #f3f4f6;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-family: 'Courier New', monospace;
  font-size: 0.9375rem;
  color: #dc2626;
}

.article-body :deep(pre) {
  background-color: #1f2937;
  color: #f3f4f6;
  padding: 1.5rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin-bottom: 1.25rem;
}

.article-body :deep(pre code) {
  background-color: transparent;
  padding: 0;
  color: #f3f4f6;
}

.article-body :deep(blockquote) {
  border-left: 4px solid #667eea;
  padding-left: 1rem;
  margin: 1.25rem 0;
  color: #4b5563;
  font-style: italic;
}

.article-body :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1.5rem 0;
}

.article-body :deep(table) {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.25rem;
}

.article-body :deep(th),
.article-body :deep(td) {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}

.article-body :deep(th) {
  background-color: #f9fafb;
  font-weight: 600;
}

.article-footer {
  padding: 2rem;
  border-top: 1px solid #e5e7eb;
}

.footer-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.back-button {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: #f3f4f6;
  color: #1f2937;
  text-decoration: none;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: all 0.2s;
}

.back-button:hover {
  background-color: #e5e7eb;
  transform: translateX(-4px);
}

.edit-button {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: #667eea;
  color: #fff;
  text-decoration: none;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: all 0.2s;
}

.edit-button:hover {
  background-color: #5568d3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.action-buttons {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.delete-button {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background-color: #dc2626;
  color: #fff;
  border: none;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.delete-button:hover {
  background-color: #b91c1c;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

/* 削除確認ダイアログ */
.dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.dialog-content {
  background-color: #fff;
  border-radius: 0.5rem;
  padding: 2rem;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.dialog-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 1rem;
}

.dialog-message {
  color: #6b7280;
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.dialog-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
  flex-wrap: wrap;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-cancel {
  background-color: #f3f4f6;
  color: #1f2937;
}

.btn-cancel:hover {
  background-color: #e5e7eb;
}

.btn-delete {
  background-color: #dc2626;
  color: #fff;
}

.btn-delete:hover:not(:disabled) {
  background-color: #b91c1c;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.error-message {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 0.75rem;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .article-detail-page {
    padding: 1rem 0.5rem;
  }

  .article-header {
    padding: 1.5rem 1rem;
  }

  .article-title {
    font-size: 1.75rem;
  }

  .breadcrumb {
    font-size: 0.8125rem;
  }

  .breadcrumb-current {
    display: block;
    margin-top: 0.25rem;
  }

  .article-body {
    padding: 1.5rem 1rem;
    font-size: 1rem;
  }

  .article-body :deep(h2) {
    font-size: 1.5rem;
  }

  .article-body :deep(h3) {
    font-size: 1.25rem;
  }

  .article-body :deep(pre) {
    padding: 1rem;
  }

  .article-footer {
    padding: 1.5rem 1rem;
  }
}
</style>
