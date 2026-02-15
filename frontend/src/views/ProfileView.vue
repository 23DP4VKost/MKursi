<template>
  <div class="profile-page">
    <section class="profile-card" v-if="user">
      <h1>Tavs konta pārskats</h1>

      <h2 class="section-title">Personīgā informācija</h2>

      <div class="profile-row">
        <span class="label">Lietotājvārds</span>
        <span class="value">{{ user.username }}</span>
      </div>

      <div class="profile-row">
        <span class="label">E-pasts</span>
        <span class="value">{{ user.email }}</span>
      </div>

      <div class="profile-row">
        <span class="label">Konts izveidots</span>
        <span class="value">{{ formatDate(user.created_at) }}</span>
      </div>

      <div class="profile-row">
        <span class="label">Loma</span>
        <span class="value">{{ user.role || 'user' }}</span>
      </div>
      
    </section>

    <section class="profile-card" v-else>
      <h1>Profils</h1>
      <p class="subtitle">Profila dati nav pieejami. Piesakies vēlreiz.</p>
      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>
    </section>

    <section class="recent-topics" v-if="recentTopics.length">
      <h2>Nesen skatītās tēmas</h2>
      <p class="subtitle">Pēdējās pievienotās vai skatītās tēmas platformā</p>

      <v-list density="comfortable" class="mt-2">
        <v-list-item
          v-for="topic in recentTopics"
          :key="topic.id"
          :to="`/topics/${topic.id}`"
          link
        >
          <v-list-item-title>{{ topic.name }}</v-list-item-title>
          <v-list-item-subtitle>
            {{ formatDate(topic.created_at) }}
          </v-list-item-subtitle>
        </v-list-item>
      </v-list>
    </section>

    <section v-else class="recent-topics empty">
      <h2>Nesenās tēmas</h2>
      <p class="subtitle">Vēl nav pievienotu tēmu.</p>
    </section>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { api } from '@/services/api'
import { currentUser } from '@/services/auth'

interface User {
  id: number
  email: string
  username: string
  role?: string
  created_at?: string
}

interface Topic {
  id: number
  name: string
  created_at?: string
}

const user = ref<User | null>(null)
const recentTopics = ref<Topic[]>([])
const errorMessage = ref('')

const loadProfile = async () => {
  try {
    errorMessage.value = ''
    if (currentUser.value) {
      user.value = currentUser.value
    }
    const { data } = await api.get('/profile')
    user.value = data.user
    recentTopics.value = data.recent_topics || []
  } catch (error) {
    console.error('Failed to load profile', error)
    errorMessage.value = 'Neizdevās ielādēt profilu. Lūdzu, mēģini vēlreiz.'
  }
}

const formatDate = (value?: string) => {
  if (!value) return ''
  return new Date(value).toLocaleDateString('lv-LV')
}

onMounted(loadProfile)
</script>

<style scoped>
.profile-page {
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.profile-card {
  padding: 24px;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
}

.profile-card h1 {
  font-size: 1.8rem;
  margin-bottom: 4px;
}

.subtitle {
  color: #6b7280;
  margin-bottom: 8px;
}

.section-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 16px 0 4px;
  color: #111827;
}

.profile-row {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
}

.label {
  font-weight: 600;
  color: #4b5563;
}

.value {
  color: #111827;
}

.error-text {
  color: #b91c1c;
  margin-top: 8px;
}

.recent-topics {
  padding: 24px;
  border-radius: 16px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
}

.recent-topics.empty {
  text-align: center;
}

.recent-topics h2 {
  margin-bottom: 4px;
}
</style>
