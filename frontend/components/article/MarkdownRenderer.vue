<template>
  <div class="markdown-content" v-html="renderedMarkdown"></div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { marked } from 'marked'

const props = defineProps<{
  content: string
}>()

// Markdownのレンダリング設定
marked.setOptions({
  breaks: true,
  gfm: true,
})

const renderedMarkdown = computed(() => {
  if (!props.content) return ''
  return marked(props.content)
})
</script>

<style scoped>
.markdown-content {
  line-height: 1.8;
  color: #1f2937;
}

/* 見出し */
.markdown-content :deep(h1),
.markdown-content :deep(h2),
.markdown-content :deep(h3),
.markdown-content :deep(h4),
.markdown-content :deep(h5),
.markdown-content :deep(h6) {
  font-weight: 700;
  line-height: 1.3;
  margin-top: 2rem;
  margin-bottom: 1rem;
  color: #111827;
}

.markdown-content :deep(h1) {
  font-size: 2.25rem;
  border-bottom: 2px solid #e5e7eb;
  padding-bottom: 0.5rem;
}

.markdown-content :deep(h2) {
  font-size: 1.875rem;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 0.5rem;
}

.markdown-content :deep(h3) {
  font-size: 1.5rem;
}

.markdown-content :deep(h4) {
  font-size: 1.25rem;
}

.markdown-content :deep(h5) {
  font-size: 1.125rem;
}

.markdown-content :deep(h6) {
  font-size: 1rem;
}

/* 段落 */
.markdown-content :deep(p) {
  margin-bottom: 1.25rem;
}

/* リンク */
.markdown-content :deep(a) {
  color: #3b82f6;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: border-color 0.2s;
}

.markdown-content :deep(a:hover) {
  border-bottom-color: #3b82f6;
}

/* リスト */
.markdown-content :deep(ul),
.markdown-content :deep(ol) {
  margin-bottom: 1.25rem;
  padding-left: 2rem;
}

.markdown-content :deep(li) {
  margin-bottom: 0.5rem;
}

/* コードブロック */
.markdown-content :deep(pre) {
  background-color: #1f2937;
  color: #f3f4f6;
  padding: 1.25rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin-bottom: 1.25rem;
  font-size: 0.875rem;
  line-height: 1.6;
}

.markdown-content :deep(code) {
  background-color: #f3f4f6;
  color: #e11d48;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-size: 0.875em;
  font-family: 'Courier New', Courier, monospace;
}

.markdown-content :deep(pre code) {
  background-color: transparent;
  color: inherit;
  padding: 0;
  font-size: inherit;
}

/* 引用 */
.markdown-content :deep(blockquote) {
  border-left: 4px solid #3b82f6;
  padding-left: 1.25rem;
  margin: 1.25rem 0;
  color: #6b7280;
  font-style: italic;
}

/* テーブル */
.markdown-content :deep(table) {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.25rem;
  overflow-x: auto;
  display: block;
}

.markdown-content :deep(th),
.markdown-content :deep(td) {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}

.markdown-content :deep(th) {
  background-color: #f9fafb;
  font-weight: 600;
}

.markdown-content :deep(tr:nth-child(even)) {
  background-color: #f9fafb;
}

/* 画像 */
.markdown-content :deep(img) {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1.25rem 0;
}

/* 水平線 */
.markdown-content :deep(hr) {
  border: none;
  border-top: 2px solid #e5e7eb;
  margin: 2rem 0;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .markdown-content :deep(h1) {
    font-size: 1.875rem;
  }

  .markdown-content :deep(h2) {
    font-size: 1.5rem;
  }

  .markdown-content :deep(h3) {
    font-size: 1.25rem;
  }

  .markdown-content :deep(pre) {
    padding: 1rem;
    font-size: 0.8125rem;
  }

  .markdown-content :deep(table) {
    font-size: 0.875rem;
  }

  .markdown-content :deep(th),
  .markdown-content :deep(td) {
    padding: 0.5rem;
  }
}
</style>
