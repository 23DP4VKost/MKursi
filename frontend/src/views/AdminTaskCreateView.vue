<template>
  <v-container class="py-6">
    <v-card>
      <v-card-title class="text-h6">Admin: Pievienot uzdevumu</v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loadingTopics" indeterminate color="primary" class="mb-4" />

        <v-alert v-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>

        <v-alert v-if="successMessage" type="success" variant="tonal" class="mb-4">
          {{ successMessage }}
        </v-alert>

        <v-form @submit.prevent="submitTask">
          <v-text-field
            v-model="form.name"
            label="Uzdevuma nosaukums"
            variant="outlined"
            maxlength="40"
            counter
            class="mb-3"
            required
          />

          <v-select
            v-model="form.topic_id"
            label="Tēma"
            variant="outlined"
            :items="topicOptions"
            item-title="title"
            item-value="value"
            class="mb-3"
            :disabled="loadingTopics || topicOptions.length === 0"
            required
          />

          <v-textarea
            v-model="form.question"
            label="Uzdevuma teksts"
            variant="outlined"
            rows="4"
            auto-grow
            class="mb-3"
            required
          />

          <v-select
            v-model="form.difficulty_level"
            label="Grūtības līmenis"
            variant="outlined"
            :items="difficultyOptions"
            clearable
            class="mb-3"
          />

          <v-text-field
            v-model="form.correct_answer"
            label="Pareizā atbilde"
            variant="outlined"
            maxlength="10"
            counter
            class="mb-4"
          />

          <v-btn
            type="submit"
            color="primary"
            :loading="saving"
            :disabled="loadingTopics || topicOptions.length === 0"
          >
            Pievienot uzdevumu
          </v-btn>
        </v-form>
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

interface MathPart {
  id: number
  name: string
  topics: Topic[]
}

interface TopicOption {
  title: string
  value: number
}

type DifficultyLevel = 'viegls' | 'videjs' | 'sarezgit'

const loadingTopics = ref(true)
const saving = ref(false)
const error = ref('')
const successMessage = ref('')
const topicOptions = ref<TopicOption[]>([])

const form = ref<{
  name: string
  question: string
  correct_answer: string
  difficulty_level: DifficultyLevel | null
  topic_id: number | null
}>({
  name: '',
  question: '',
  correct_answer: '',
  difficulty_level: null,
  topic_id: null,
})

const difficultyOptions = [
  { title: 'Viegls', value: 'viegls' },
  { title: 'Vidējs', value: 'videjs' },
  { title: 'Sarežģīts', value: 'sarezgit' },
] as const

const normalizedPayload = computed(() => ({
  name: form.value.name.trim(),
  question: form.value.question.trim(),
  correct_answer: form.value.correct_answer.trim() || null,
  difficulty_level: form.value.difficulty_level,
  topic_id: form.value.topic_id,
}))

const loadTopics = async () => {
  loadingTopics.value = true
  error.value = ''

  try {
    const { data } = await api.get<MathPart[]>('/topics')
    topicOptions.value = data.flatMap((part) =>
      part.topics.map((topic) => ({
        title: `${part.name} - ${topic.name}`,
        value: topic.id,
      })),
    )
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt tēmas.'
  } finally {
    loadingTopics.value = false
  }
}

const resetForm = () => {
  form.value = {
    name: '',
    question: '',
    correct_answer: '',
    difficulty_level: null,
    topic_id: null,
  }
}

const submitTask = async () => {
  error.value = ''
  successMessage.value = ''

  if (!normalizedPayload.value.topic_id) {
    error.value = 'Izvēlies tēmu.'
    return
  }

  if (!normalizedPayload.value.name || !normalizedPayload.value.question) {
    error.value = 'Aizpildi obligātos laukus.'
    return
  }

  saving.value = true
  try {
    await api.post('/admin/tasks', normalizedPayload.value)
    successMessage.value = 'Uzdevums veiksmīgi pievienots.'
    resetForm()
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās pievienot uzdevumu.'
  } finally {
    saving.value = false
  }
}

onMounted(loadTopics)
</script>
