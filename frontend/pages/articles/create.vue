<template>
  <div class="article-create-page">
    <div class="page-header">
      <h1 class="page-title">新しい記事を作成</h1>
      <NuxtLink to="/articles" class="back-link">
        ← 記事一覧に戻る
      </NuxtLink>
    </div>

    <form @submit.prevent="handleSubmit" class="create-form">
      <div class="form-layout">
        <!-- 左側: フォーム入力 -->
        <div class="form-section">
          <div class="form-group">
            <label for="title" class="form-label">
              タイトル <span class="required">*</span>
            </label>
            <input
              id="title"
              v-model="formData.title"
              type="text"
              class="form-input"
              placeholder="記事のタイトルを入力"
              required
            />
            <p v-if="errors.title" class="error-message">{{ errors.title }}</p>
          </div>

          <div class="form-group">
            <label for="excerpt" class="form-label">
              抜粋 <span class="required">*</span>
            </label>
            <textarea
              id="excerpt"
              v-model="formData.excerpt"
              class="form-textarea"
              rows="3"
              placeholder="記事の概要を入力（120文字程度）"
              required
            ></textarea>
            <p v-if="errors.excerpt" class="error-message">{{ errors.excerpt }}</p>
          </div>

          <div class="form-group">
            <label for="content" class="form-label">
              本文（Markdown） <span class="required">*</span>
            </label>
            <textarea
              id="content"
              v-model="formData.content"
              class="form-textarea markdown-editor"
              rows="15"
              placeholder="Markdown形式で記事本文を入力"
              required
            ></textarea>
            <p v-if="errors.content" class="error-message">{{ errors.content }}</p>
            <p class="help-text">
              Markdown形式で入力できます。見出し: # ## ###、リスト: - *、コード: `code`
            </p>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="category" class="form-label">カテゴリ</label>
              <input
                id="category"
                v-model="formData.category"
                type="text"
                class="form-input"
                list="category-suggestions"
                placeholder="カテゴリを入力または選択"
              />
              <datalist id="category-suggestions">
                <option v-for="cat in existingCategories" :key="cat" :value="cat" />
              </datalist>
              <p class="help-text">既存のカテゴリから選択、または新規作成できます</p>
            </div>

            <div class="form-group">
              <label for="readTime" class="form-label">読了時間（分）</label>
              <input
                id="readTime"
                v-model.number="formData.readTime"
                type="number"
                class="form-input"
                min="1"
                placeholder="5"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="thumbnail" class="form-label">サムネイルURL</label>
            <input
              id="thumbnail"
              v-model="formData.thumbnail"
              type="url"
              class="form-input"
              placeholder="https://example.com/image.jpg"
            />
            <p v-if="errors.thumbnail" class="error-message">{{ errors.thumbnail }}</p>
          </div>

          <div class="form-group">
            <label for="tags" class="form-label">タグ</label>
            <input
              id="tags"
              v-model="tagsInput"
              type="text"
              class="form-input"
              list="tag-suggestions"
              placeholder="カンマ区切りで入力 (例: Vue.js, Nuxt, Frontend)"
            />
            <datalist id="tag-suggestions">
              <option v-for="tag in existingTags" :key="tag" :value="tag" />
            </datalist>
            <p class="help-text">カンマで区切って複数のタグを入力できます。既存のタグから選択も可能です</p>
          </div>

          <div class="form-actions">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="isSubmitting"
            >
              {{ isSubmitting ? '作成中...' : '記事を作成' }}
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              @click="togglePreview"
            >
              {{ showPreview ? 'プレビューを閉じる' : 'プレビューを表示' }}
            </button>
          </div>

          <p v-if="submitError" class="error-message submit-error">
            {{ submitError }}
          </p>
        </div>

        <!-- 右側: プレビュー -->
        <div v-if="showPreview" class="preview-section">
          <h2 class="preview-title">プレビュー</h2>
          <div class="preview-content">
            <article class="article-preview">
              <h1 class="article-title">{{ formData.title || 'タイトル' }}</h1>

              <div class="article-meta">
                <span v-if="formData.category" class="category-badge">
                  {{ formData.category }}
                </span>
                <span class="article-date">{{ currentDate }}</span>
                <span v-if="formData.readTime" class="read-time">
                  読了時間: {{ formData.readTime }}分
                </span>
              </div>

              <div v-if="parsedTags.length > 0" class="article-tags">
                <span v-for="tag in parsedTags" :key="tag" class="tag">
                  #{{ tag }}
                </span>
              </div>

              <img
                v-if="formData.thumbnail"
                :src="formData.thumbnail"
                :alt="formData.title"
                class="article-thumbnail"
              />

              <div class="article-excerpt">{{ formData.excerpt || '抜粋が表示されます' }}</div>

              <div class="article-body" v-html="renderedContent"></div>
            </article>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { marked } from 'marked'

const router = useRouter()

interface FormData {
  title: string
  excerpt: string
  content: string
  thumbnail: string
  category: string
  tags: string[]
  readTime: number | null
}

const formData = ref<FormData>({
  title: '',
  excerpt: '',
  content: '',
  thumbnail: '',
  category: '',
  tags: [],
  readTime: null
})

const tagsInput = ref('')
const errors = ref<Record<string, string>>({})
const isSubmitting = ref(false)
const submitError = ref('')
const showPreview = ref(false)

// 既存のカテゴリとタグを格納
const existingCategories = ref<string[]>([])
const existingTags = ref<string[]>([])

// カテゴリを取得
const fetchCategories = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/categories')
    if (response.ok) {
      const data = await response.json()
      existingCategories.value = data.map((item: any) => item.name)
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
      const data = await response.json()
      existingTags.value = data.map((item: any) => item.name)
    }
  } catch (error) {
    console.error('タグの取得に失敗しました:', error)
  }
}

// タグを配列に変換
const parsedTags = computed(() => {
  if (!tagsInput.value) return []
  return tagsInput.value
    .split(',')
    .map(tag => tag.trim())
    .filter(tag => tag.length > 0)
})

// Markdownをレンダリング
const renderedContent = computed(() => {
  if (!formData.value.content) return '<p class="empty-content">本文を入力してください</p>'
  return marked(formData.value.content)
})

// 現在の日付
const currentDate = computed(() => {
  return new Date().toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

// プレビュー表示切り替え
const togglePreview = () => {
  showPreview.value = !showPreview.value
}

// バリデーション
const validateForm = (): boolean => {
  errors.value = {}

  if (!formData.value.title.trim()) {
    errors.value.title = 'タイトルは必須です'
  } else if (formData.value.title.length > 255) {
    errors.value.title = 'タイトルは255文字以内で入力してください'
  }

  if (!formData.value.excerpt.trim()) {
    errors.value.excerpt = '抜粋は必須です'
  }

  if (!formData.value.content.trim()) {
    errors.value.content = '本文は必須です'
  }

  if (formData.value.thumbnail && !isValidUrl(formData.value.thumbnail)) {
    errors.value.thumbnail = '有効なURLを入力してください'
  }

  return Object.keys(errors.value).length === 0
}

// URL検証
const isValidUrl = (url: string): boolean => {
  try {
    new URL(url)
    return true
  } catch {
    return false
  }
}

// フォーム送信
const handleSubmit = async () => {
  if (!validateForm()) {
    submitError.value = '入力内容を確認してください'
    return
  }

  isSubmitting.value = true
  submitError.value = ''

  try {
    const requestData = {
      title: formData.value.title,
      excerpt: formData.value.excerpt,
      content: formData.value.content,
      thumbnail: formData.value.thumbnail || null,
      category: formData.value.category || null,
      tags: parsedTags.value.length > 0 ? parsedTags.value : null,
      read_time: formData.value.readTime || null
    }

    const response = await fetch('http://localhost:8000/api/articles', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(requestData)
    })

    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.message || '記事の作成に失敗しました')
    }

    const createdArticle = await response.json()

    // 作成した記事の詳細ページへリダイレクト
    router.push(`/articles/${createdArticle.id}`)
  } catch (error) {
    submitError.value = error instanceof Error ? error.message : '記事の作成に失敗しました'
    console.error('記事作成エラー:', error)
  } finally {
    isSubmitting.value = false
  }
}

// データを読み込み
onMounted(() => {
  fetchCategories()
  fetchTags()
})

// SEO対策
useHead({
  title: '記事を作成 - ブログアプリ',
  meta: [
    {
      name: 'description',
      content: '新しい記事を作成します'
    }
  ]
})
</script>

<style scoped>
.article-create-page {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 800;
  color: #1f2937;
  margin: 0;
}

.back-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}

.back-link:hover {
  color: #5568d3;
}

.create-form {
  background-color: #fff;
  border-radius: 0.5rem;
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.form-layout.has-preview {
  grid-template-columns: 1fr 1fr;
}

.form-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-label {
  font-weight: 600;
  color: #1f2937;
  font-size: 0.875rem;
}

.required {
  color: #dc2626;
}

.form-input,
.form-textarea,
.form-select {
  padding: 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s;
  font-family: inherit;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  outline: none;
  border-color: #667eea;
}

.markdown-editor {
  font-family: 'Courier New', monospace;
  font-size: 0.9375rem;
}

.help-text {
  font-size: 0.8125rem;
  color: #6b7280;
  margin: 0;
}

.error-message {
  color: #dc2626;
  font-size: 0.8125rem;
  margin: 0;
}

.submit-error {
  font-size: 1rem;
  padding: 0.75rem;
  background-color: #fee2e2;
  border-radius: 0.375rem;
  border: 1px solid #dc2626;
}

.form-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.875rem 2rem;
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

.btn-primary {
  background-color: #667eea;
  color: #fff;
}

.btn-primary:hover:not(:disabled) {
  background-color: #5568d3;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
  background-color: #f3f4f6;
  color: #1f2937;
}

.btn-secondary:hover {
  background-color: #e5e7eb;
}

/* プレビューセクション */
.preview-section {
  border-left: 2px solid #e5e7eb;
  padding-left: 2rem;
  position: sticky;
  top: 2rem;
  max-height: calc(100vh - 4rem);
  overflow-y: auto;
}

.preview-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 1rem;
}

.preview-content {
  background-color: #f9fafb;
  border-radius: 0.5rem;
  padding: 2rem;
}

.article-preview .article-title {
  font-size: 2rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 1rem;
  line-height: 1.3;
}

.article-preview .article-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  margin-bottom: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}

.category-badge {
  padding: 0.25rem 0.75rem;
  background-color: #667eea;
  color: #fff;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.8125rem;
}

.article-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tag {
  padding: 0.25rem 0.75rem;
  background-color: #e5e7eb;
  color: #4b5563;
  border-radius: 0.375rem;
  font-size: 0.8125rem;
  font-weight: 500;
}

.article-thumbnail {
  width: 100%;
  border-radius: 0.5rem;
  margin-bottom: 1.5rem;
}

.article-excerpt {
  font-size: 1.0625rem;
  color: #4b5563;
  line-height: 1.6;
  margin-bottom: 1.5rem;
  padding: 1rem;
  background-color: #fff;
  border-left: 4px solid #667eea;
  border-radius: 0.25rem;
}

.article-preview .article-body {
  line-height: 1.8;
  font-size: 1rem;
  color: #374151;
}

.article-preview .article-body :deep(h1),
.article-preview .article-body :deep(h2),
.article-preview .article-body :deep(h3) {
  font-weight: 700;
  color: #1f2937;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
}

.article-preview .article-body :deep(h2) {
  font-size: 1.5rem;
}

.article-preview .article-body :deep(h3) {
  font-size: 1.25rem;
}

.article-preview .article-body :deep(p) {
  margin-bottom: 1rem;
}

.article-preview .article-body :deep(code) {
  background-color: #e5e7eb;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-family: 'Courier New', monospace;
  font-size: 0.9375rem;
}

.article-preview .article-body :deep(pre) {
  background-color: #1f2937;
  color: #f3f4f6;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin-bottom: 1rem;
}

.article-preview .article-body :deep(pre code) {
  background-color: transparent;
  color: #f3f4f6;
}

.empty-content {
  color: #9ca3af;
  font-style: italic;
}

/* モバイル対応 */
@media (max-width: 1024px) {
  .preview-section {
    position: static;
    max-height: none;
    border-left: none;
    border-top: 2px solid #e5e7eb;
    padding-left: 0;
    padding-top: 2rem;
    margin-top: 2rem;
  }
}

@media (max-width: 768px) {
  .article-create-page {
    padding: 1rem 0.5rem;
  }

  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .page-title {
    font-size: 1.5rem;
  }

  .create-form {
    padding: 1.5rem 1rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
  }

  .preview-content {
    padding: 1rem;
  }

  .article-preview .article-title {
    font-size: 1.5rem;
  }
}
</style>
