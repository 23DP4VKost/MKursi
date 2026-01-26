<template>
  <v-app class="app-shell">
    <div class="background-texture" aria-hidden="true"></div>

    <SiteHeader />

    <v-main class="page-surface">
      <v-container fluid class="page-container">
        <router-view />
      </v-container>
    </v-main>

    <SiteFooter />
  </v-app>
</template>

<script setup lang="ts">
import SiteHeader from '@/components/SiteHeader.vue'
import SiteFooter from '@/components/SiteFooter.vue'
</script>

<style scoped>
.app-shell {
  min-height: 100vh;
  position: relative;
  background: radial-gradient(circle at 20% 20%, rgba(13, 96, 216, 0.08), transparent 32%),
    radial-gradient(circle at 80% 0%, rgba(255, 140, 66, 0.12), transparent 30%),
    #f5f7fb;
  color: #0f172a;
}

.background-texture {
  position: fixed;
  inset: 0;
  background-image:
    linear-gradient(120deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0) 45%),
    radial-gradient(circle at 15% 35%, rgba(255, 255, 255, 0.5), transparent 35%),
    radial-gradient(circle at 85% 65%, rgba(255, 255, 255, 0.36), transparent 30%);
  pointer-events: none;
  z-index: 0;
}

.page-surface {
  position: relative;
  z-index: 1;
}

.page-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 32px 20px 64px;
}

@media (min-width: 1280px) {
  .page-container {
    padding-inline: 32px;
  }
}
</style>
