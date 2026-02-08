<template>
  <v-container class="py-6">
    <v-card>
      <v-card-title class="text-h6">Tēmas</v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>
        <v-list v-else>
          <template v-for="part in parts" :key="part.id">
            <v-list-subheader>
              {{ part.name }} 
            </v-list-subheader>
            <v-list-item
              v-for="topic in part.topics"
              :key="topic.id"
              :to="{ name: 'topic-theories', params: { id: topic.id } }"
              link
            >
              <v-list-item-title>{{ topic.name }}</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="part.topics.length === 0">
              <v-list-item-title>Nav nevienas tēmas.</v-list-item-title>
            </v-list-item>
          </template>
          <v-list-item v-if="parts.length === 0">
            <v-list-item-title>Nav nevienas tēmas.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { api } from '@/services/api'

interface Topic {
  id: number
  name: string
}

interface MathematicsPart {
  id: number
  code: string
  name: string
  topics: Topic[]
}

const parts = ref<MathematicsPart[]>([])
const loading = ref(true)
const error = ref('')

const loadTopics = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get<MathematicsPart[]>('/topics')
    parts.value = response.data
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt tēmas.'
  } finally {
    loading.value = false
  }
}

onMounted(loadTopics)
</script>
