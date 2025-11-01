<template>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-content">
        <div class="footer-section">
          <h3 class="footer-title">ブログアプリ</h3>
          <p class="footer-description">
            Nuxt.js + Laravel で作られたモダンなブログシステム
          </p>
        </div>

        <div class="footer-section">
          <h4 class="section-title">リンク</h4>
          <ul class="footer-links">
            <li>
              <NuxtLink to="/" class="footer-link">ホーム</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/articles" class="footer-link">記事一覧</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/about" class="footer-link">About</NuxtLink>
            </li>
          </ul>
        </div>

        <div class="footer-section">
          <h4 class="section-title">カテゴリ</h4>
          <ul class="footer-links">
            <li>
              <NuxtLink to="/categories/tech" class="footer-link">テクノロジー</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/categories/lifestyle" class="footer-link">ライフスタイル</NuxtLink>
            </li>
            <li>
              <NuxtLink to="/categories/tutorial" class="footer-link">チュートリアル</NuxtLink>
            </li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p class="copyright">
          &copy; {{ currentYear }} ブログアプリ. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const currentYear = computed(() => new Date().getFullYear())
</script>

<style scoped>
.footer {
  background-color: #1f2937;
  color: #e5e7eb;
  margin-top: auto;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 3rem 1.5rem 1.5rem;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.footer-section {
  display: flex;
  flex-direction: column;
}

.footer-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  color: #fff;
}

.footer-description {
  color: #9ca3af;
  font-size: 0.875rem;
  line-height: 1.5;
  margin: 0;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  margin: 0 0 1rem 0;
  color: #fff;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.footer-link {
  color: #9ca3af;
  text-decoration: none;
  font-size: 0.875rem;
  transition: color 0.2s;
}

.footer-link:hover {
  color: #3b82f6;
}

.footer-bottom {
  padding-top: 2rem;
  border-top: 1px solid #374151;
  text-align: center;
}

.copyright {
  color: #9ca3af;
  font-size: 0.875rem;
  margin: 0;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .footer-container {
    padding: 2rem 1rem 1rem;
  }

  .footer-content {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
}
</style>
