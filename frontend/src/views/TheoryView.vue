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
        <div v-else class="text-body-1" style="white-space: pre-line;">
          {{ content }}
        </div>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '@/services/api'

interface Theory {
  id: number
  subtopic_name: string
  content: string
  topic_id: number
}

const route = useRoute()
const loading = ref(true)
const error = ref('')
const theory = ref<Theory | null>(null)

const title = computed(() => theory.value?.subtopic_name ?? 'Teorija')
const content = computed(() => theory.value?.content ?? '')

const loadTheory = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get<Theory>(`/theories/${route.params.id}`)
    theory.value = response.data
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt teoriju.'
  } finally {
    loading.value = false
  }
}

onMounted(loadTheory)
</script>
