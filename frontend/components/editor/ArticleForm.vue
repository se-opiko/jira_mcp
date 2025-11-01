<template>
  <form @submit.prevent="handleSubmit" class="article-form">
    <div class="form-section">
      <div class="form-group">
        <label for="title" class="form-label">タイトル *</label>
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
        <label for="category" class="form-label">カテゴリ</label>
        <select
          id="category"
          v-model="formData.category"
          class="form-select"
        >
          <option value="">カテゴリを選択</option>
          <option value="tech">テクノロジー</option>
          <option value="lifestyle">ライフスタイル</option>
          <option value="tutorial">チュートリアル</option>
          <option value="news">ニュース</option>
        </select>
      </div>

      <div class="form-group">
        <label for="tags" class="form-label">タグ</label>
        <input
          id="tags"
          v-model="tagsInput"
          type="text"
          class="form-input"
          placeholder="タグをカンマ区切りで入力（例: Vue.js, Laravel, TypeScript）"
        />
        <div v-if="formData.tags.length" class="tags-preview">
          <span
            v-for="(tag, index) in formData.tags"
            :key="index"
            class="tag"
          >
            {{ tag }}
            <button
              type="button"
              @click="removeTag(index)"
              class="tag-remove"
            >
              ×
            </button>
          </span>
        </div>
      </div>

      <div class="form-group">
        <label for="excerpt" class="form-label">要約</label>
        <textarea
          id="excerpt"
          v-model="formData.excerpt"
          class="form-textarea"
          rows="3"
          placeholder="記事の要約を入力（任意）"
        ></textarea>
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
      </div>
    </div>

    <div class="form-section">
      <label class="form-label">記事本文 *</label>
      <EditorMarkdownEditor v-model="formData.content" />
      <p v-if="errors.content" class="error-message">{{ errors.content }}</p>
    </div>

    <div class="form-actions">
      <button
        type="button"
        @click="handleCancel"
        class="btn btn-secondary"
      >
        キャンセル
      </button>
      <button
        type="submit"
        :disabled="isSubmitting"
        class="btn btn-primary"
      >
        {{ isSubmitting ? '保存中...' : submitButtonText }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue'

interface ArticleFormData {
  title: string
  category: string
  tags: string[]
  excerpt: string
  thumbnail: string
  content: string
}

interface Props {
  initialData?: Partial<ArticleFormData>
  submitButtonText?: string
}

const props = withDefaults(defineProps<Props>(), {
  submitButtonText: '保存'
})

const emit = defineEmits<{
  submit: [data: ArticleFormData]
  cancel: []
}>()

const formData = ref<ArticleFormData>({
  title: '',
  category: '',
  tags: [],
  excerpt: '',
  thumbnail: '',
  content: '',
  ...props.initialData
})

const tagsInput = ref(formData.value.tags.join(', '))
const errors = ref<Partial<Record<keyof ArticleFormData, string>>>({})
const isSubmitting = ref(false)

// タグ入力の監視
watch(tagsInput, (newValue) => {
  formData.value.tags = newValue
    .split(',')
    .map(tag => tag.trim())
    .filter(tag => tag.length > 0)
})

const removeTag = (index: number) => {
  formData.value.tags.splice(index, 1)
  tagsInput.value = formData.value.tags.join(', ')
}

const validateForm = (): boolean => {
  errors.value = {}

  if (!formData.value.title.trim()) {
    errors.value.title = 'タイトルは必須です'
  }

  if (!formData.value.content.trim()) {
    errors.value.content = '本文は必須です'
  }

  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  isSubmitting.value = true

  try {
    emit('submit', formData.value)
  } finally {
    isSubmitting.value = false
  }
}

const handleCancel = () => {
  emit('cancel')
}
</script>

<style scoped>
.article-form {
  background-color: #fff;
  border-radius: 0.5rem;
  padding: 2rem;
}

.form-section {
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.9375rem;
  transition: all 0.2s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  font-family: inherit;
}

.tags-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.tag {
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.375rem 0.75rem;
  background-color: #eff6ff;
  color: #1e40af;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
}

.tag-remove {
  background: none;
  border: none;
  color: #1e40af;
  font-size: 1.125rem;
  line-height: 1;
  cursor: pointer;
  padding: 0;
  margin: 0;
  width: 16px;
  height: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color 0.2s;
}

.tag-remove:hover {
  background-color: rgba(30, 64, 175, 0.1);
}

.error-message {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 0.375rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background-color: #3b82f6;
  color: #fff;
}

.btn-primary:hover:not(:disabled) {
  background-color: #2563eb;
}

.btn-secondary {
  background-color: #f3f4f6;
  color: #374151;
}

.btn-secondary:hover {
  background-color: #e5e7eb;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .article-form {
    padding: 1.5rem;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
