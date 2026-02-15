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
        <div v-else>

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
            <template v-for="part in filteredAndSortedParts" :key="part.id">
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
                <v-list-item-title>Nav neviena tēma.</v-list-item-title>
              </v-list-item>
            </template>
            <v-list-item v-if="filteredAndSortedParts.length === 0 && (searchQuery || filterPart)">
              <v-list-item-title>Nav atrasta neviena tēma.</v-list-item-title>
            </v-list-item>
            <v-list-item v-if="parts.length === 0">
              <v-list-item-title>Nav neviena tēma.</v-list-item-title>
            </v-list-item>
          </v-list>
        </div>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
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
    ...parts.value.map(part => ({
      value: part.code,
      title: part.name
    }))
  ]
})

const filteredAndSortedParts = computed(() => {
  let result = parts.value.map(part => ({
    ...part,
    topics: [...part.topics]
  }))


  if (filterPart.value) {
    result = result.filter(part => part.code === filterPart.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.map(part => ({
      ...part,
      topics: part.topics.filter(topic => 
        topic.name.toLowerCase().includes(query) ||
        part.name.toLowerCase().includes(query)
      )
    })).filter(part => part.topics.length > 0)
  }


  result.forEach(part => {
    part.topics.sort((a, b) => {
      switch (sortOrder.value) {
        case 'name-asc':
          return a.name.localeCompare(b.name)
        case 'name-desc':
          return b.name.localeCompare(a.name)
        default:
          return 0
      }
    })
  })


  result.sort((a, b) => a.name.localeCompare(b.name))

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
