<template>
  <nav aria-label="パンくずリスト" class="breadcrumb">
    <ol class="breadcrumb-list">
      <li
        v-for="(item, index) in items"
        :key="index"
        class="breadcrumb-item"
      >
        <NuxtLink
          v-if="index < items.length - 1"
          :to="item.path"
          class="breadcrumb-link"
        >
          {{ item.label }}
        </NuxtLink>
        <span v-else class="breadcrumb-current">
          {{ item.label }}
        </span>
        <span v-if="index < items.length - 1" class="breadcrumb-separator">
          /
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
interface BreadcrumbItem {
  label: string
  path: string
}

defineProps<{
  items: BreadcrumbItem[]
}>()
</script>

<style scoped>
.breadcrumb {
  margin-bottom: 1.5rem;
}

.breadcrumb-list {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.875rem;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.breadcrumb-link {
  color: #6b7280;
  text-decoration: none;
  transition: color 0.2s;
}

.breadcrumb-link:hover {
  color: #3b82f6;
}

.breadcrumb-current {
  color: #1f2937;
  font-weight: 600;
}

.breadcrumb-separator {
  color: #9ca3af;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .breadcrumb-list {
    font-size: 0.8125rem;
  }
}
</style>
