<template>
  <v-container class="py-6">
    <v-card>
      <v-card-title class="text-h6">Admin: Jautājumu atbildēšana</v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>

        <v-list v-else>
          <v-list-item v-for="item in questions" :key="item.id" class="mb-4">
            <v-list-item-title class="mb-1">{{ item.question }}</v-list-item-title>
            <v-list-item-subtitle class="mb-2">
              Lietotājs: {{ item.user?.username }} ({{ item.user?.email }})
            </v-list-item-subtitle>

            <v-chip :color="item.status === 'answered' ? 'success' : 'warning'" size="small" class="mb-3">
              {{ item.status === 'answered' ? 'Atbildēts' : 'Gaida atbildi' }}
            </v-chip>

            <v-textarea
              v-model="answers[item.id]"
              label="Atbilde"
              variant="outlined"
              rows="3"
              auto-grow
            />

            <div class="d-flex ga-2 flex-wrap">
              <v-btn
                color="primary"
                :loading="savingId === item.id"
                @click="saveAnswer(item.id)"
              >
                Saglabāt atbildi
              </v-btn>

              <v-btn
                v-if="item.status === 'answered'"
                color="error"
                variant="outlined"
                :loading="deletingId === item.id"
                @click="deleteAnswer(item.id)"
              >
                Dzēst atbildi
              </v-btn>
            </div>
          </v-list-item>

          <v-list-item v-if="questions.length === 0">
            <v-list-item-title>Nav jautājumu.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { api } from '@/services/api'

interface QuestionItem {
  id: number
  question: string
  answer: string | null
  status: 'pending' | 'answered'
  user?: {
    username: string
    email: string
  }
}

const questions = ref<QuestionItem[]>([])
const answers = ref<Record<number, string>>({})
const loading = ref(true)
const error = ref('')
const savingId = ref<number | null>(null)
const deletingId = ref<number | null>(null)

const loadQuestions = async () => {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get<QuestionItem[]>('/admin/questions')
    questions.value = data
    answers.value = data.reduce((acc, item) => {
      acc[item.id] = item.answer || ''
      return acc
    }, {} as Record<number, string>)
  } catch (err: any) {
    console.error(err)
    error.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās ielādēt jautājumus.'
  } finally {
    loading.value = false
  }
}

const saveAnswer = async (questionId: number) => {
  const answer = (answers.value[questionId] || '').trim()
  if (!answer) {
    return
  }

  const question = questions.value.find((item) => item.id === questionId)
  if (!question) {
    return
  }

  savingId.value = questionId
  try {
    if (question.status === 'answered') {
      await api.put(`/admin/questions/${questionId}/answer`, { answer })
    } else {
      await api.post(`/admin/questions/${questionId}/answer`, { answer })
    }
    await loadQuestions()
  } catch (err) {
    console.error(err)
  } finally {
    savingId.value = null
  }
}

const deleteAnswer = async (questionId: number) => {
  deletingId.value = questionId
  try {
    await api.delete(`/admin/questions/${questionId}/answer`)
    await loadQuestions()
  } catch (err) {
    console.error(err)
  } finally {
    deletingId.value = null
  }
}

onMounted(loadQuestions)
</script>
