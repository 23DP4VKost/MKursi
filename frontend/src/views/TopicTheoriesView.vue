<template>
  <v-container class="py-6">
    <v-card>
      <v-card-title class="text-h6">{{ title }}</v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>
        <v-list v-else>
          <v-list-item
            v-for="theory in theories"
            :key="theory.id"
            :to="{ name: 'theory', params: { id: theory.id } }"
            link
          >
            <v-list-item-title>{{ theory.subtopic_name }}</v-list-item-title>
          </v-list-item>
          <v-list-item v-if="theories.length === 0">
            <v-list-item-title>Nav nevienas apakštēmas.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '@/services/api'

interface TheorySummary {
  id: number
  subtopic_name: string
  topic_id: number
}

interface TopicWithTheories {
  id: number
  name: string
  theories: TheorySummary[]
}

const route = useRoute()
const loading = ref(true)
const error = ref('')
const topic = ref<TopicWithTheories | null>(null)

const theories = computed(() => topic.value?.theories ?? [])
const title = computed(() => topic.value?.name ?? 'Apakštēmas')

const loadTheories = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get<TopicWithTheories>(`/topics/${route.params.id}/theories`)
    topic.value = response.data
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt apakštēmas.'
  } finally {
    loading.value = false
  }
}

onMounted(loadTheories)
</script>
