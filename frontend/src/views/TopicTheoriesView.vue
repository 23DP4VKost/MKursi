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
        <div v-else>
          <div class="mb-4 d-flex gap-3 flex-wrap">
            <v-text-field
              v-model="searchQuery"
              label="Meklēt teoriju"
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              density="compact"
              hide-details
              clearable
              style="max-width: 300px;"
            />
            <v-select
              v-model="sortOrder"
              :items="sortOptions"
              label="Kārtot"
              variant="outlined"
              density="compact"
              hide-details
              style="max-width: 200px;"
            />
          </div>

          <v-list>
            <v-list-item
              v-for="theory in filteredAndSortedTheories"
              :key="theory.id"
              :to="{ name: 'theory', params: { id: theory.id } }"
              link
            >
              <v-list-item-title>{{ theory.subtopic_name }}</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="filteredAndSortedTheories.length === 0 && searchQuery">
              <v-list-item-title>Nav atrasta neviena teorija.</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="theories.length === 0">
              <v-list-item-title>Nav nevienas apakštēmas.</v-list-item-title>
            </v-list-item>
          </v-list>
        </div>
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
const searchQuery = ref('')
const sortOrder = ref('name-asc')

const sortOptions = [
  { value: 'name-asc', title: 'Nosaukums (A-Z)' },
  { value: 'name-desc', title: 'Nosaukums (Z-A)' },
]

const theories = computed(() => topic.value?.theories ?? [])
const title = computed(() => topic.value?.name ?? 'Apakštēmas')

const filteredAndSortedTheories = computed(() => {
  let result = [...theories.value]

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(theory => 
      theory.subtopic_name.toLowerCase().includes(query)
    )
  }

  result.sort((a, b) => {
    switch (sortOrder.value) {
      case 'name-asc':
        return a.subtopic_name.localeCompare(b.subtopic_name)
      case 'name-desc':
        return b.subtopic_name.localeCompare(a.subtopic_name)
      default:
        return 0
    }
  })

  return result
})

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
