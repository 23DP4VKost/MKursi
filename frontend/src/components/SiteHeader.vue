<template>

  <header class="site-header">
    <v-app-bar flat density="comfortable" class="app-bar" color="transparent">
      <div class="brand">
        <span class="brand-name">MKursi</span>
      </div>
    <v-spacer/>

      <nav class="nav-links" aria-label="Galvenā navigācija">
        <v-btn variant="text" class="nav-btn" to="/">Sākums</v-btn>
        <v-btn variant="text"class="nav-btn" to="/par-mums">Par mums</v-btn>
        <v-btn variant="text"class="nav-btn" to="/kontakti">Kontakti</v-btn>
        <template v-if="isLoggedIn">
          <v-btn variant="text" class="nav-btn" to="/profile">Profils</v-btn>
          <v-btn variant="text" class="nav-btn" @click="handleLogout">Iziet</v-btn>
        </template>
        <template v-else>
          <v-btn variant="text" class="nav-btn" to="/login">Pieteikties</v-btn>
          <v-btn variant="text" class="nav-btn" to="/register">Reģistrēties</v-btn>
        </template>

      </nav>

    </v-app-bar>

  </header>

</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { currentUser, fetchCurrentUser, logout } from '@/services/auth'

const router = useRouter()

const isLoggedIn = computed(() => !!currentUser.value)

const handleLogout = async () => {
  await logout()
  router.push('/')
}

onMounted(() => {
  fetchCurrentUser()
})
</script>

<style scoped>
.site-header {
  position: sticky;
  top: 0;
  z-index: 10;
}

.app-bar {
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  background: #0f172a !important;
  backdrop-filter: blur(12px);
  padding-inline: 14px;
}

.brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
}

.brand-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0d60d8, #f97316);
  color: #ffffff;
  font-weight: 700;
  letter-spacing: 0.5px;
}

.brand-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: #e2e8f0;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 4px;
}

.nav-btn {
  text-transform: none;
  font-weight: 600;
  letter-spacing: 0;
  color: #e2e8f0;
}

.nav-btn:hover {
  color: #7bdcff;
}

.nav-cta {
  border-radius: 10px;
  font-weight: 700;
  box-shadow: 0 10px 30px rgba(13, 96, 216, 0.18);
}

@media (max-width: 768px) {
  .nav-links {
    gap: 0;
  }

  .nav-btn {
    min-width: auto;
    padding-inline: 10px;
    font-size: 0.9rem;
  }

  .nav-cta {
    margin-left: 4px;
  }
}
</style>
