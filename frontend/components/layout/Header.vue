<template>
  <header class="header">
    <div class="header-container">
      <div class="logo">
        <NuxtLink to="/" class="logo-link">
          <h1>ブログアプリ</h1>
        </NuxtLink>
      </div>

      <button
        class="mobile-menu-toggle"
        @click="toggleMobileMenu"
        :aria-label="isMobileMenuOpen ? 'メニューを閉じる' : 'メニューを開く'"
      >
        <span class="hamburger" :class="{ 'is-active': isMobileMenuOpen }">
          <span></span>
          <span></span>
          <span></span>
        </span>
      </button>

      <nav class="nav" :class="{ 'is-open': isMobileMenuOpen }">
        <ul class="nav-list">
          <li class="nav-item">
            <NuxtLink to="/" class="nav-link" @click="closeMobileMenu">
              ホーム
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink to="/articles" class="nav-link" @click="closeMobileMenu">
              記事一覧
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink to="/articles/create" class="nav-link" @click="closeMobileMenu">
              記事作成
            </NuxtLink>
          </li>
          <li class="nav-item">
            <NuxtLink to="/about" class="nav-link" @click="closeMobileMenu">
              About
            </NuxtLink>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}
</script>

<style scoped>
.header {
  background-color: #fff;
  border-bottom: 1px solid #e5e7eb;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.logo-link {
  text-decoration: none;
  color: inherit;
  transition: color 0.2s;
}

.logo-link:hover {
  color: #3b82f6;
}

.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
  z-index: 101;
}

.hamburger {
  display: flex;
  flex-direction: column;
  width: 24px;
  gap: 4px;
}

.hamburger span {
  display: block;
  width: 100%;
  height: 2px;
  background-color: #1f2937;
  transition: all 0.3s ease;
}

.hamburger.is-active span:nth-child(1) {
  transform: rotate(45deg) translate(5px, 5px);
}

.hamburger.is-active span:nth-child(2) {
  opacity: 0;
}

.hamburger.is-active span:nth-child(3) {
  transform: rotate(-45deg) translate(6px, -6px);
}

.nav-list {
  display: flex;
  gap: 2rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-link {
  text-decoration: none;
  color: #4b5563;
  font-weight: 500;
  transition: color 0.2s;
  padding: 0.5rem 0;
  position: relative;
}

.nav-link:hover {
  color: #3b82f6;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #3b82f6;
  transition: width 0.3s ease;
}

.nav-link:hover::after,
.nav-link.router-link-active::after {
  width: 100%;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .mobile-menu-toggle {
    display: block;
  }

  .nav {
    position: fixed;
    top: 0;
    right: -100%;
    width: 70%;
    max-width: 300px;
    height: 100vh;
    background-color: #fff;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
    transition: right 0.3s ease;
    padding-top: 5rem;
  }

  .nav.is-open {
    right: 0;
  }

  .nav-list {
    flex-direction: column;
    gap: 0;
    padding: 1rem;
  }

  .nav-item {
    border-bottom: 1px solid #e5e7eb;
  }

  .nav-link {
    display: block;
    padding: 1rem;
  }
}

/* タブレット対応 */
@media (min-width: 769px) and (max-width: 1024px) {
  .header-container {
    padding: 1rem;
  }

  .nav-list {
    gap: 1.5rem;
  }
}
</style>
