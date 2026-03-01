<template>
  <v-container class="py-6">
    <v-card class="mb-6">
      <v-card-title class="text-h6">Uzdot jautājumu</v-card-title>
      <v-divider />
      <v-card-text>
        <v-alert v-if="submitError" type="error" variant="tonal" class="mb-4">
          {{ submitError }}
        </v-alert>
        <v-alert v-if="submitSuccess" type="success" variant="tonal" class="mb-4">
          Jautājums nosūtīts.
        </v-alert>

        <v-textarea
          v-model="questionText"
          label="Tavs jautājums"
          variant="outlined"
          rows="4"
          auto-grow
          counter="2000"
          :maxlength="2000"
        />

        <div class="d-flex justify-end">
          <v-btn color="primary" :loading="submitting" @click="submitQuestion">
            Nosūtīt jautājumu
          </v-btn>
        </div>
      </v-card-text>
    </v-card>

    <v-card>
      <v-card-title class="text-h6">Mani jautājumi</v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="loadError" type="error" variant="tonal" class="mb-4">
          {{ loadError }}
        </v-alert>

        <v-list v-else>
          <v-list-item v-for="item in questions" :key="item.id" class="mb-3">
            <template #prepend>
              <v-chip :color="item.status === 'answered' ? 'success' : 'warning'" size="small" class="mr-3">
                {{ item.status === 'answered' ? 'Atbildēts' : 'Gaida atbildi' }}
              </v-chip>
            </template>

            <v-list-item-title class="mb-2">{{ item.question }}</v-list-item-title>
            <v-list-item-subtitle>
              <div v-if="item.answer">Atbilde: {{ item.answer }}</div>
              <div v-else>Atbilde vēl nav sniegta.</div>
            </v-list-item-subtitle>
          </v-list-item>

          <v-list-item v-if="questions.length === 0">
            <v-list-item-title>Nav neviena jautājuma.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { api } from '@/services/api'

interface UserQuestion {
  id: number
  question: string
  answer: string | null
  status: 'pending' | 'answered'
}

const questions = ref<UserQuestion[]>([])
const loading = ref(true)
const loadError = ref('')
const router = useRouter()

const questionText = ref('')
const submitting = ref(false)
const submitError = ref('')
const submitSuccess = ref(false)

const loadQuestions = async () => {
  loading.value = true
  loadError.value = ''

  try {
    const { data } = await api.get<UserQuestion[]>('/questions/my')
    questions.value = data
  } catch (error) {
    console.error(error)
    loadError.value = 'Neizdevās ielādēt jautājumus.'
  } finally {
    loading.value = false
  }
}

const submitQuestion = async () => {
  submitError.value = ''
  submitSuccess.value = false

  if (!questionText.value.trim()) {
    submitError.value = 'Ievadi jautājumu.'
    return
  }

  submitting.value = true
  try {
    await api.post('/questions', { question: questionText.value.trim() })
    questionText.value = ''
    submitSuccess.value = true
    await loadQuestions()
  } catch (error) {
    console.error(error)

    if (axios.isAxiosError(error)) {
      if (error.response?.status === 401) {
        submitError.value = 'Sesija beigusies. Lūdzu, piesakies vēlreiz.'
        router.push({ name: 'login' })
      } else {
        const message = error.response?.data?.message
        submitError.value = typeof message === 'string' && message.trim()
          ? message
          : 'Neizdevās nosūtīt jautājumu.'
      }
    } else {
      submitError.value = 'Neizdevās nosūtīt jautājumu.'
    }
  } finally {
    submitting.value = false
  }
}

onMounted(loadQuestions)
</script>
