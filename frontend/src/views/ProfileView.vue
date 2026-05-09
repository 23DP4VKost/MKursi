<template>
  <div class="profile-page view-page">
    <section class="profile-card view-card" v-if="user">
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

      <div class="profile-actions">
        <v-btn
          color="error"
          variant="flat"
          class="delete-account-btn"
          :loading="isDeleting"
          @click="isDeleteDialogOpen = true"
        >
          Dzēst kontu
        </v-btn>
      </div>
      
    </section>

    <section class="profile-card view-card" v-else>
      <h1>Profils</h1>
      <p class="subtitle view-subtitle">Profila dati nav pieejami. Piesakies vēlreiz.</p>
      <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>
    </section>

    <v-dialog v-model="isDeleteDialogOpen" max-width="520">
      <v-card class="delete-dialog">
        <v-card-title>Apstiprini konta dzēšanu</v-card-title>
        <v-card-text>
          Šo darbību nevar atsaukt. Tavs konts, profils un ar to saistītie dati tiks neatgriezeniski dzēsti.
        </v-card-text>
        <v-card-actions class="dialog-actions">
          <v-btn variant="text" @click="isDeleteDialogOpen = false">Atcelt</v-btn>
          <v-btn
            color="error"
            variant="flat"
            :loading="isDeleting"
            @click="handleDeleteAccount"
          >
            Dzēst kontu
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '@/services/api'
import { currentUser, deleteAccount } from '@/services/auth'

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
const isDeleting = ref(false)
const isDeleteDialogOpen = ref(false)
const router = useRouter()

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
    console.error('Neizdevās ielādēt profilu', error)
    errorMessage.value = 'Neizdevās ielādēt profilu. Lūdzu, mēģini vēlreiz.'
  }
}

const formatDate = (value?: string) => {
  if (!value) return ''
  return new Date(value).toLocaleDateString('lv-LV')
}

const handleDeleteAccount = async () => {
  try {
    isDeleting.value = true
    await deleteAccount()
    isDeleteDialogOpen.value = false
    router.push('/login')
  } catch (error) {
    console.error('Neizdevās dzēst kontu', error)
    errorMessage.value = 'Neizdevās dzēst kontu. Lūdzu, mēģini vēlreiz.'
  } finally {
    isDeleting.value = false
  }
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

.profile-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

.delete-account-btn {
  min-width: 160px;
  text-transform: none;
  font-weight: 700;
}

.delete-dialog {
  padding: 8px 4px;
}

.dialog-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  padding: 0 16px 16px;
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
