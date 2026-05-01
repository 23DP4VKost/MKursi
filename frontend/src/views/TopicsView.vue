<template>
  <v-container class="py-6">
    <v-card v-if="isAdmin" class="mb-6">
      <v-card-title class="d-flex align-center justify-space-between flex-wrap ga-3 text-h6">
        <span>Matemātikas daļas</span>
        <v-btn
          color="primary"
          prepend-icon="mdi-plus"
          @click="openCreatePartDialog"
        >
          Pievienot daļu
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-alert v-if="partActionError" type="error" variant="tonal" class="mb-4">
          {{ partActionError }}
        </v-alert>
        <v-alert v-else-if="partActionSuccess" type="success" variant="tonal" class="mb-4">
          {{ partActionSuccess }}
        </v-alert>

        <v-list>
          <v-list-item
            v-for="part in sortedParts"
            :key="part.id"
          >
            <v-list-item-title>{{ part.name }}</v-list-item-title>
            <template #append>
              <div class="d-flex ga-2">
                <v-btn
                  size="small"
                  variant="outlined"
                  @click="openEditPartDialog(part)"
                >
                  Rediģēt
                </v-btn>
                <v-btn
                  size="small"
                  color="error"
                  variant="outlined"
                  :loading="deletingPartId === part.id"
                  @click="deletePart(part)"
                >
                  Dzēst
                </v-btn>
              </div>
            </template>
          </v-list-item>
          <v-list-item v-if="parts.length === 0">
            <v-list-item-title>Nav nevienas daļas.</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>

    <v-card>
      <v-card-title class="d-flex align-center justify-space-between flex-wrap ga-3 text-h6">
        <span>Tēmas</span>
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
          <v-list-item v-if="filteredTopics.length === 0 && (searchQuery || filterPart)">
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

    <v-dialog v-model="partDialog" max-width="520">
      <v-card>
        <v-card-title class="text-h6">
          {{ editingPartId ? 'Rediģēt daļu' : 'Pievienot daļu' }}
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-form @submit.prevent="submitPart">
            <v-text-field
              v-model="partForm.code"
              label="Daļas kods"
              variant="outlined"
              maxlength="3"
              counter
              class="mb-3"
              required
            />

            <v-text-field
              v-model="partForm.name"
              label="Daļas nosaukums"
              variant="outlined"
              maxlength="40"
              counter
              class="mb-3"
              required
            />

            <div class="d-flex justify-end ga-2">
              <v-btn type="button" variant="text" :disabled="savingPart" @click="closePartDialog">
                Atcelt
              </v-btn>
              <v-btn
                color="primary"
                type="submit"
                :loading="savingPart"
              >
                {{ editingPartId ? 'Saglabāt' : 'Pievienot' }}
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

interface TopicOption {
  title: string
  value: number
}

const parts = ref<MathematicsPart[]>([])
const loading = ref(true)
const error = ref('')
const actionError = ref('')
const actionSuccess = ref('')
const partActionError = ref('')
const partActionSuccess = ref('')
const searchQuery = ref('')
const filterPart = ref('')
const sortOrder = ref('name-asc')
const topicDialog = ref(false)
const savingTopic = ref(false)
const deletingTopicId = ref<number | null>(null)
const editingTopicId = ref<number | null>(null)
const partDialog = ref(false)
const savingPart = ref(false)
const deletingPartId = ref<number | null>(null)
const editingPartId = ref<number | null>(null)
const topicForm = ref({
  name: '',
  math_part_id: null as number | null,
})
const partForm = ref({
  code: '',
  name: '',
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

const sortedParts = computed(() =>
  [...parts.value].sort((a, b) => a.name.localeCompare(b.name)),
)

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
  actionError.value = ''
  partActionError.value = ''

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

const resetPartForm = () => {
  partForm.value = {
    code: '',
    name: '',
  }
}

const openCreatePartDialog = () => {
  partActionError.value = ''
  partActionSuccess.value = ''
  editingPartId.value = null
  resetPartForm()
  partDialog.value = true
}

const openEditPartDialog = (part: MathematicsPart) => {
  partActionError.value = ''
  partActionSuccess.value = ''
  editingPartId.value = part.id
  partForm.value = {
    code: part.code,
    name: part.name,
  }
  partDialog.value = true
}

const closePartDialog = () => {
  partDialog.value = false
  editingPartId.value = null
  resetPartForm()
}

const submitPart = async () => {
  partActionError.value = ''
  partActionSuccess.value = ''

  const normalizedCode = partForm.value.code.trim().toUpperCase()
  const normalizedName = partForm.value.name.trim()

  if (normalizedCode.length !== 3) {
    partActionError.value = 'Daļas kodam jābūt tieši 3 simboliem.'
    return
  }

  if (!normalizedName) {
    partActionError.value = 'Ievadi daļas nosaukumu.'
    return
  }

  savingPart.value = true
  try {
    const payload = {
      code: normalizedCode,
      name: normalizedName,
    }

    if (editingPartId.value) {
      await api.put(`/admin/mathematics-parts/${editingPartId.value}`, payload)
      partActionSuccess.value = 'Daļa atjaunināta.'
    } else {
      await api.post('/admin/mathematics-parts', payload)
      partActionSuccess.value = 'Daļa veiksmīgi pievienota.'
    }

    closePartDialog()
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    partActionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās saglabāt daļu.'
  } finally {
    savingPart.value = false
  }
}

const deletePart = async (part: MathematicsPart) => {
  partActionError.value = ''
  partActionSuccess.value = ''

  const hasTopics = part.topics.length > 0
  const confirmText = hasTopics
    ? 'Šai daļai ir tēmas, kas arī tiks dzēstas. Vai turpināt?'
    : 'Vai tiešām dzēst šo daļu?'

  if (!window.confirm(confirmText)) {
    return
  }

  deletingPartId.value = part.id
  try {
    await api.delete(`/admin/mathematics-parts/${part.id}`)
    partActionSuccess.value = 'Daļa dzēsta.'
    if (editingPartId.value === part.id) {
      closePartDialog()
    }
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    partActionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās dzēst daļu.'
  } finally {
    deletingPartId.value = null
  }
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
