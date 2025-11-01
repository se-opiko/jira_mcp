<template>
  <div class="app-layout">
    <LayoutHeader />

    <main class="main-content">
      <div class="content-container">
        <div class="content-wrapper">
          <slot />
        </div>

        <LayoutSidebar class="sidebar-wrapper" />
      </div>
    </main>

    <LayoutFooter />
  </div>
</template>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  background-color: #f9fafb;
}

.content-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 2rem;
  align-items: start;
}

.content-wrapper {
  min-width: 0;
}

/* タブレット対応 */
@media (max-width: 1024px) {
  .content-container {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .sidebar-wrapper {
    order: 2;
  }
}

/* モバイル対応 */
@media (max-width: 768px) {
  .content-container {
    padding: 1rem;
  }
}
</style>
