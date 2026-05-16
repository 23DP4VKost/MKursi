<template>
  <v-container class="py-6 view-page">
    <v-card class="view-card">
      <v-card-title class="d-flex align-center justify-space-between flex-wrap ga-3 text-h6">
        <span>Tēmas</span>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>
        <div class="mb-4 d-flex gap-3 flex-wrap">
          <v-text-field
            v-model="searchQuery"
            label="Meklēt tēmu"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="compact"
            hide-details
            clearable
            style="max-width: 300px;"
          />
          <v-select
            v-model="filterPart"
            :items="partFilterOptions"
            label="Filtrēt pēc daļas"
            variant="outlined"
            density="compact"
            hide-details
            clearable
            style="max-width: 200px;"
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
            v-for="topic in filteredTopics"
            :key="topic.id"
          >
            <v-list-item-title>
              <RouterLink :to="{ name: 'topic-theories', params: { id: topic.id } }" class="topic-link">
                {{ topic.name }}
              </RouterLink>
            </v-list-item-title>
            <v-list-item-subtitle>
              {{ topic.partName }}
            </v-list-item-subtitle>
            
          </v-list-item>
          <v-list-item v-if="filteredTopics.length === 0 && (searchQuery || filterPart)">
            <v-list-item-title>Nav atrasta neviena tēma.</v-list-item-title>
          </v-list-item>
          <v-list-item v-if="parts.length === 0">
            <v-list-item-title>Nav nevienas tēmas.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { api } from '../services/api'

interface Topic {
  id: number
  name: string
  math_part_id: number
}

interface FlatTopic extends Topic {
  partName: string
  partCode: string
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
const searchQuery = ref('')
const filterPart = ref('')
const sortOrder = ref('name-asc')

const sortOptions = [
  { value: 'name-asc', title: 'Nosaukums (A-Z)' },
  { value: 'name-desc', title: 'Nosaukums (Z-A)' },
]

const partFilterOptions = computed(() => {
  return [
    { value: '', title: 'Visi' },
    ...parts.value.map((part) => ({
      value: part.code,
      title: part.name,
    })),
  ]
})

const filteredTopics = computed<FlatTopic[]>(() => {
  let result = parts.value.flatMap((part) =>
    part.topics.map((topic) => ({
      ...topic,
      partName: part.name,
      partCode: part.code,
    })),
  )

  if (filterPart.value) {
    result = result.filter((topic) => topic.partCode === filterPart.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter((topic) =>
      topic.name.toLowerCase().includes(query) ||
      topic.partName.toLowerCase().includes(query),
    )
  }

  result.sort((a, b) => {
    switch (sortOrder.value) {
      case 'name-asc':
        return a.name.localeCompare(b.name)
      case 'name-desc':
        return b.name.localeCompare(a.name)
      default:
        return 0
    }
  })

  return result
})

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

<style scoped>
.topic-link {
  color: inherit;
  text-decoration: none;
}

.topic-link:hover {
  text-decoration: underline;
}
</style>
