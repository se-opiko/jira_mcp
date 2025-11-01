<template>
  <div class="markdown-editor">
    <div class="editor-toolbar">
      <button
        v-for="tool in toolbarButtons"
        :key="tool.name"
        @click="insertMarkdown(tool.syntax)"
        :title="tool.title"
        class="toolbar-btn"
      >
        {{ tool.icon }}
      </button>

      <div class="toolbar-divider"></div>

      <button
        @click="togglePreview"
        class="toolbar-btn"
        :class="{ active: showPreview }"
      >
        {{ showPreview ? '編集' : 'プレビュー' }}
      </button>
    </div>

    <div class="editor-container" :class="{ 'split-view': showPreview }">
      <textarea
        v-if="!showPreview"
        ref="textarea"
        v-model="content"
        @input="handleInput"
        class="editor-textarea"
        placeholder="Markdownで記事を書く..."
      ></textarea>

      <div v-if="showPreview" class="editor-preview">
        <ArticleMarkdownRenderer :content="content" />
      </div>
    </div>

    <div class="editor-footer">
      <span class="char-count">{{ content.length }} 文字</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  modelValue: string
}>()

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const content = ref(props.modelValue || '')
const textarea = ref<HTMLTextAreaElement>()
const showPreview = ref(false)

const toolbarButtons = [
  { name: 'bold', icon: 'B', title: '太字', syntax: { prefix: '**', suffix: '**', placeholder: '太字' } },
  { name: 'italic', icon: 'I', title: '斜体', syntax: { prefix: '*', suffix: '*', placeholder: '斜体' } },
  { name: 'heading', icon: 'H', title: '見出し', syntax: { prefix: '## ', suffix: '', placeholder: '見出し' } },
  { name: 'link', icon: '🔗', title: 'リンク', syntax: { prefix: '[', suffix: '](url)', placeholder: 'リンクテキスト' } },
  { name: 'code', icon: '<>', title: 'コード', syntax: { prefix: '`', suffix: '`', placeholder: 'コード' } },
  { name: 'quote', icon: '"', title: '引用', syntax: { prefix: '> ', suffix: '', placeholder: '引用文' } },
  { name: 'list', icon: '•', title: 'リスト', syntax: { prefix: '- ', suffix: '', placeholder: 'リスト項目' } },
]

const handleInput = () => {
  emit('update:modelValue', content.value)
}

const insertMarkdown = (syntax: { prefix: string; suffix: string; placeholder: string }) => {
  const el = textarea.value
  if (!el) return

  const start = el.selectionStart
  const end = el.selectionEnd
  const selectedText = content.value.substring(start, end)
  const textToInsert = selectedText || syntax.placeholder

  const newText =
    content.value.substring(0, start) +
    syntax.prefix +
    textToInsert +
    syntax.suffix +
    content.value.substring(end)

  content.value = newText
  emit('update:modelValue', content.value)

  // カーソル位置を調整
  setTimeout(() => {
    el.focus()
    const newCursorPos = start + syntax.prefix.length
    el.setSelectionRange(newCursorPos, newCursorPos + textToInsert.length)
  }, 0)
}

const togglePreview = () => {
  showPreview.value = !showPreview.value
}

watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
  }
})
</script>

<style scoped>
.markdown-editor {
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
  background-color: #fff;
}

.editor-toolbar {
  display: flex;
  gap: 0.25rem;
  padding: 0.75rem;
  background-color: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
  flex-wrap: wrap;
}

.toolbar-btn {
  padding: 0.5rem 0.75rem;
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
  font-size: 0.875rem;
}

.toolbar-btn:hover {
  background-color: #f3f4f6;
  border-color: #9ca3af;
}

.toolbar-btn.active {
  background-color: #3b82f6;
  color: #fff;
  border-color: #3b82f6;
}

.toolbar-divider {
  width: 1px;
  background-color: #d1d5db;
  margin: 0 0.5rem;
}

.editor-container {
  min-height: 400px;
  display: flex;
}

.editor-textarea {
  width: 100%;
  min-height: 400px;
  padding: 1.5rem;
  border: none;
  resize: vertical;
  font-family: 'Courier New', Courier, monospace;
  font-size: 0.9375rem;
  line-height: 1.8;
  outline: none;
}

.editor-preview {
  width: 100%;
  padding: 1.5rem;
  overflow-y: auto;
  background-color: #fff;
}

.split-view .editor-textarea,
.split-view .editor-preview {
  width: 50%;
  border-right: 1px solid #e5e7eb;
}

.editor-footer {
  padding: 0.5rem 1rem;
  background-color: #f9fafb;
  border-top: 1px solid #e5e7eb;
  text-align: right;
}

.char-count {
  font-size: 0.75rem;
  color: #6b7280;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .editor-toolbar {
    padding: 0.5rem;
  }

  .toolbar-btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.8125rem;
  }

  .editor-container {
    min-height: 300px;
  }

  .editor-textarea {
    min-height: 300px;
    padding: 1rem;
    font-size: 0.875rem;
  }

  .split-view {
    flex-direction: column;
  }

  .split-view .editor-textarea,
  .split-view .editor-preview {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #e5e7eb;
  }

  .editor-preview {
    padding: 1rem;
  }
}
</style>
