<template>
  <nav aria-label="ページネーション" class="pagination">
    <button
      @click="handlePrevious"
      :disabled="currentPage === 1"
      class="pagination-btn"
      aria-label="前のページ"
    >
      &laquo; 前へ
    </button>

    <div class="pagination-pages">
      <button
        v-for="page in visiblePages"
        :key="page"
        @click="handlePageClick(page)"
        :class="['pagination-page', { active: page === currentPage, ellipsis: page === '...' }]"
        :disabled="page === '...'"
      >
        {{ page }}
      </button>
    </div>

    <button
      @click="handleNext"
      :disabled="currentPage === totalPages"
      class="pagination-btn"
      aria-label="次のページ"
    >
      次へ &raquo;
    </button>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  currentPage: number
  totalPages: number
  maxVisiblePages?: number
}>()

const emit = defineEmits<{
  'update:currentPage': [page: number]
}>()

const maxVisible = props.maxVisiblePages || 5

const visiblePages = computed(() => {
  const pages: (number | string)[] = []

  if (props.totalPages <= maxVisible + 2) {
    // 全ページを表示
    for (let i = 1; i <= props.totalPages; i++) {
      pages.push(i)
    }
  } else {
    // 省略記号を含むページネーション
    const halfVisible = Math.floor(maxVisible / 2)
    let startPage = Math.max(2, props.currentPage - halfVisible)
    let endPage = Math.min(props.totalPages - 1, props.currentPage + halfVisible)

    // 開始ページの調整
    if (props.currentPage <= halfVisible + 1) {
      endPage = maxVisible
    }

    // 終了ページの調整
    if (props.currentPage >= props.totalPages - halfVisible) {
      startPage = props.totalPages - maxVisible + 1
    }

    // 最初のページ
    pages.push(1)

    // 開始省略記号
    if (startPage > 2) {
      pages.push('...')
    }

    // 中間ページ
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }

    // 終了省略記号
    if (endPage < props.totalPages - 1) {
      pages.push('...')
    }

    // 最後のページ
    if (props.totalPages > 1) {
      pages.push(props.totalPages)
    }
  }

  return pages
})

const handlePageClick = (page: number | string) => {
  if (typeof page === 'number' && page !== props.currentPage) {
    emit('update:currentPage', page)
  }
}

const handlePrevious = () => {
  if (props.currentPage > 1) {
    emit('update:currentPage', props.currentPage - 1)
  }
}

const handleNext = () => {
  if (props.currentPage < props.totalPages) {
    emit('update:currentPage', props.currentPage + 1)
  }
}
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 2rem;
  flex-wrap: wrap;
}

.pagination-btn {
  padding: 0.625rem 1rem;
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  color: #4b5563;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  background-color: #f3f4f6;
  border-color: #9ca3af;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-pages {
  display: flex;
  gap: 0.25rem;
}

.pagination-page {
  min-width: 2.5rem;
  padding: 0.625rem;
  background-color: #fff;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  color: #4b5563;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
}

.pagination-page:hover:not(:disabled):not(.active) {
  background-color: #f3f4f6;
  border-color: #9ca3af;
}

.pagination-page.active {
  background-color: #3b82f6;
  color: #fff;
  border-color: #3b82f6;
}

.pagination-page.ellipsis {
  border: none;
  cursor: default;
  background-color: transparent;
}

.pagination-page.ellipsis:hover {
  background-color: transparent;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .pagination {
    gap: 0.375rem;
  }

  .pagination-btn {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
  }

  .pagination-page {
    min-width: 2rem;
    padding: 0.5rem;
    font-size: 0.875rem;
  }
}
</style>
