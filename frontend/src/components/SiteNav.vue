<template>
  <v-navigation-drawer app permanent>
    <v-list>
      <v-list-item to="/" title="Sākums" />
      <v-list-item to="/topics" title="Tēmas" />
      <v-list-item to="/tasks" title="Uzdevumi" />
      <v-list-item v-if="currentUser" to="/my-questions" title="Mani jautājumi" />
      <v-list-item v-if="currentUser?.role === 'admin'" to="/admin/questions" title="Admin jautājumi" />
    </v-list>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { currentUser, fetchCurrentUser } from '@/services/auth'

onMounted(async () => {
  if (!currentUser.value) {
    await fetchCurrentUser()
  }
})
</script>
