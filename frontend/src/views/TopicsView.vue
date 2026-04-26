<template>
  <v-container class="py-6">
    <v-card>
      <v-card-title class="text-h6">Tēmas
        <v-btn
          v-if="isAdmin"
          color="primary"
          prepend-icon="mdi-plus"
          @click="openCreateDialog"
        >
          Pievienot tēmu
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />
        <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
          {{ error }}
        </v-alert>
        <v-alert v-else-if="actionError" type="error" variant="tonal" class="mb-4">
          {{ actionError }}
        </v-alert>
        <v-alert v-else-if="actionSuccess" type="success" variant="tonal" class="mb-4">
          {{ actionSuccess }}
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
          <template v-for="part in filteredAndSortedParts" :key="part.id">
            <v-list-subheader>
              {{ part.name }}
            </v-list-subheader>
            <v-list-item
              v-for="topic in part.topics"
              :key="topic.id"
            >
              <v-list-item-title>
                <RouterLink :to="{ name: 'topic-theories', params: { id: topic.id } }" class="topic-link">
                  {{ topic.name }}
                </RouterLink>
              </v-list-item-title>
              <template #append>
                <div v-if="isAdmin" class="d-flex ga-2">
                  <v-btn
                    size="small"
                    variant="outlined"
                    @click.stop="openEditDialog(topic)"
                  >
                    Rediģēt
                  </v-btn>
                  <v-btn
                    size="small"
                    color="error"
                    variant="outlined"
                    :loading="deletingTopicId === topic.id"
                    @click.stop="deleteTopic(topic.id)"
                  >
                    Dzēst
                  </v-btn>
                </div>
              </template>
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
      </v-card-text>
    </v-card>

    <v-dialog v-model="topicDialog" max-width="520">
      <v-card>
        <v-card-title class="text-h6">
          {{ editingTopicId ? 'Rediģēt tēmu' : 'Pievienot tēmu' }}
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-form @submit.prevent="submitTopic">
            <v-text-field
              v-model="topicForm.name"
              label="Tēmas nosaukums"
              variant="outlined"
              maxlength="45"
              counter
              class="mb-3"
              required
            />

            <v-select
              v-model="topicForm.math_part_id"
              :items="partOptions"
              item-title="title"
              item-value="value"
              label="Matemātikas daļa"
              variant="outlined"
              class="mb-3"
              :disabled="parts.length === 0"
              required
            />

            <div class="d-flex justify-end ga-2">
              <v-btn type="button" variant="text" :disabled="savingTopic" @click="closeTopicDialog">
                Atcelt
              </v-btn>
              <v-btn
                color="primary"
                type="submit"
                :loading="savingTopic"
                :disabled="parts.length === 0"
              >
                {{ editingTopicId ? 'Saglabāt' : 'Pievienot' }}
              </v-btn>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { api } from '@/services/api'
import { currentUser } from '@/services/auth'

interface Topic {
  id: number
  name: string
  math_part_id: number
}

interface MathematicsPart {
  id: number
  code: string
  name: string
  topics: Topic[]
}

interface TopicOption {
  title: string
  value: number
}

const parts = ref<MathematicsPart[]>([])
const loading = ref(true)
const error = ref('')
const actionError = ref('')
const actionSuccess = ref('')
const searchQuery = ref('')
const filterPart = ref('')
const sortOrder = ref('name-asc')
const topicDialog = ref(false)
const savingTopic = ref(false)
const deletingTopicId = ref<number | null>(null)
const editingTopicId = ref<number | null>(null)
const topicForm = ref({
  name: '',
  math_part_id: null as number | null,
})

const isAdmin = computed(() => currentUser.value?.role === 'admin')

const sortOptions = [
  { value: 'name-asc', title: 'Nosaukums (A-Z)' },
  { value: 'name-desc', title: 'Nosaukums (Z-A)' },
]

const partOptions = computed<TopicOption[]>(() =>
  parts.value.map((part) => ({
    title: part.name,
    value: part.id,
  })),
)

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
  actionError.value = ''

  try {
    const response = await api.get<MathematicsPart[]>('/topics')
    parts.value = response.data
    if (!topicForm.value.math_part_id && response.data.length > 0) {
      topicForm.value.math_part_id = response.data[0].id
    }
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt tēmas.'
  } finally {
    loading.value = false
  }
}

const resetTopicForm = () => {
  topicForm.value = {
    name: '',
    math_part_id: parts.value[0]?.id ?? null,
  }
}

const openCreateDialog = () => {
  actionError.value = ''
  actionSuccess.value = ''
  editingTopicId.value = null
  resetTopicForm()
  topicDialog.value = true
}

const openEditDialog = (topic: Topic) => {
  actionError.value = ''
  actionSuccess.value = ''
  editingTopicId.value = topic.id
  topicForm.value = {
    name: topic.name,
    math_part_id: topic.math_part_id,
  }
  topicDialog.value = true
}

const closeTopicDialog = () => {
  topicDialog.value = false
  editingTopicId.value = null
  resetTopicForm()
}

const submitTopic = async () => {
  actionError.value = ''
  actionSuccess.value = ''

  if (!topicForm.value.name.trim()) {
    actionError.value = 'Ievadi tēmas nosaukumu.'
    return
  }

  if (!topicForm.value.math_part_id) {
    actionError.value = 'Izvēlies matemātikas daļu.'
    return
  }

  savingTopic.value = true
  try {
    const payload = {
      name: topicForm.value.name.trim(),
      math_part_id: topicForm.value.math_part_id,
    }

    if (editingTopicId.value) {
      await api.put(`/admin/topics/${editingTopicId.value}`, payload)
      actionSuccess.value = 'Tēma atjaunināta.'
    } else {
      await api.post('/admin/topics', payload)
      actionSuccess.value = 'Tēma veiksmīgi pievienota.'
    }

    closeTopicDialog()
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    actionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās saglabāt tēmu.'
  } finally {
    savingTopic.value = false
  }
}

const deleteTopic = async (topicId: number) => {
  actionError.value = ''
  actionSuccess.value = ''

  if (!window.confirm('Vai tiešām dzēst šo tēmu?')) {
    return
  }

  deletingTopicId.value = topicId
  try {
    await api.delete(`/admin/topics/${topicId}`)
    actionSuccess.value = 'Tēma dzēsta.'
    if (editingTopicId.value === topicId) {
      closeTopicDialog()
    }
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    actionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās dzēst tēmu.'
  } finally {
    deletingTopicId.value = null
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
