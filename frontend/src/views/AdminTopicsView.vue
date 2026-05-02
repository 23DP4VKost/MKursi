<template>
  <v-container class="py-6">
    <v-card class="mb-6">
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
            v-for="part in parts"
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

        <v-expansion-panels>
          <v-expansion-panel
            v-for="topic in topics"
            :key="topic.id"
          >
            <v-expansion-panel-title>
              <div class="d-flex align-center justify-space-between w-100 pe-4">
                <div>
                  <div class="font-weight-bold">{{ topic.name }}</div>
                  <div class="text-caption">{{ topic.partName }}</div>
                </div>
                <div class="d-flex ga-2" @click.stop>
                  <v-btn
                    size="small"
                    variant="outlined"
                    @click.stop="openEditDialog(topic)"
                  >
                    Rediģēt tēmu
                  </v-btn>
                  <v-btn
                    size="small"
                    color="error"
                    variant="outlined"
                    :loading="deletingTopicId === topic.id"
                    @click.stop="deleteTopic(topic.id)"
                  >
                    Dzēst tēmu
                  </v-btn>
                </div>
              </div>
            </v-expansion-panel-title>
            <v-expansion-panel-text>
              <v-divider class="mb-4" />
              <div class="mb-4">
                <div class="mb-3 d-flex align-center justify-space-between">
                  <span class="text-subtitle-2">Apakštēmas</span>
                  <v-btn
                    size="small"
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="openCreateTheoryDialog(topic)"
                  >
                    Pievienot apakštēmu
                  </v-btn>
                </div>
                <v-alert v-if="theoryActionError && selectedTopicId === topic.id" type="error" variant="tonal" class="mb-4">
                  {{ theoryActionError }}
                </v-alert>
                <v-alert v-else-if="theoryActionSuccess && selectedTopicId === topic.id" type="success" variant="tonal" class="mb-4">
                  {{ theoryActionSuccess }}
                </v-alert>
                <v-list v-if="topic.theories && topic.theories.length > 0">
                  <v-list-item
                    v-for="theory in topic.theories"
                    :key="theory.id"
                  >
                    <v-list-item-title>{{ theory.subtopic_name }}</v-list-item-title>
                    <template #append>
                      <div class="d-flex ga-2">
                        <v-btn
                          size="small"
                          variant="outlined"
                          @click.stop="openEditTheoryDialog(theory, topic)"
                        >
                          Rediģēt
                        </v-btn>
                        <v-btn
                          size="small"
                          color="error"
                          variant="outlined"
                          :loading="deletingTheoryId === theory.id"
                          @click.stop="deleteTheory(theory.id)"
                        >
                          Dzēst
                        </v-btn>
                      </div>
                    </template>
                  </v-list-item>
                </v-list>
                <v-list v-else>
                  <v-list-item>
                    <v-list-item-title>Nav neviena apakštēma.</v-list-item-title>
                  </v-list-item>
                </v-list>
              </div>
            </v-expansion-panel-text>
          </v-expansion-panel>
          <v-expansion-panel v-if="topics.length === 0">
            <v-expansion-panel-title>Nav neviena tēma.</v-expansion-panel-title>
          </v-expansion-panel>
        </v-expansion-panels>
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

    <v-dialog v-model="theoryDialog" max-width="700">
      <v-card>
        <v-card-title class="text-h6">
          {{ editingTheoryId ? 'Rediģēt apakštēmu' : 'Pievienot apakštēmu' }}
        </v-card-title>
        <v-divider />
        <v-card-text>
          <v-form @submit.prevent="submitTheory">
            <v-text-field
              v-model="theoryForm.subtopic_name"
              label="Apakštēmas nosaukums"
              variant="outlined"
              maxlength="100"
              counter
              class="mb-3"
              required
            />

              <div class="d-flex justify-end ga-2">
              <v-btn type="button" variant="text" :disabled="savingTheory" @click="closeTheoryDialog">
                Atcelt
              </v-btn>
              <v-btn
                color="primary"
                type="submit"
                :loading="savingTheory"
              >
                {{ editingTheoryId ? 'Saglabāt' : 'Pievienot' }}
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

interface Theory {
  id: number
  subtopic_name: string
  topic_id: number
}

interface Topic {
  id: number
  name: string
  math_part_id: number
  theories?: Theory[]
}

interface FlatTopic extends Topic {
  partName: string
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
const theoryActionError = ref('')
const theoryActionSuccess = ref('')
const topicDialog = ref(false)
const savingTopic = ref(false)
const deletingTopicId = ref<number | null>(null)
const editingTopicId = ref<number | null>(null)
const partDialog = ref(false)
const savingPart = ref(false)
const deletingPartId = ref<number | null>(null)
const editingPartId = ref<number | null>(null)
const theoryDialog = ref(false)
const savingTheory = ref(false)
const deletingTheoryId = ref<number | null>(null)
const editingTheoryId = ref<number | null>(null)
const selectedTopicId = ref<number | null>(null)
const topicForm = ref({
  name: '',
  math_part_id: null as number | null,
})
const partForm = ref({
  code: '',
  name: '',
})
const theoryForm = ref({
  subtopic_name: '',
})

const partOptions = computed<TopicOption[]>(() =>
  parts.value.map((part) => ({
    title: part.name,
    value: part.id,
  })),
)

const topics = computed<FlatTopic[]>(() =>
  parts.value.flatMap((part) =>
    (part.topics ?? []).map((topic) => ({
      ...topic,
      partName: part.name,
    })),
  ),
)


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

const resetPartForm = () => {
  partForm.value = {
    code: '',
    name: '',
  }
}

const openCreateDialog = () => {
  actionError.value = ''
  actionSuccess.value = ''
  editingTopicId.value = null
  resetTopicForm()
  topicDialog.value = true
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

const openCreateTheoryDialog = (topic: Topic) => {
  theoryActionError.value = ''
  theoryActionSuccess.value = ''
  selectedTopicId.value = topic.id
  editingTheoryId.value = null
  theoryForm.value.subtopic_name = ''
  theoryDialog.value = true
}

const openEditTheoryDialog = (theory: Theory, topic: Topic) => {
  theoryActionError.value = ''
  theoryActionSuccess.value = ''
  selectedTopicId.value = topic.id
  editingTheoryId.value = theory.id
  theoryForm.value.subtopic_name = theory.subtopic_name
  theoryDialog.value = true
}

const closeTheoryDialog = () => {
  theoryDialog.value = false
  editingTheoryId.value = null
  selectedTopicId.value = null
  theoryForm.value.subtopic_name = ''
}

const submitTheory = async () => {
  theoryActionError.value = ''
  theoryActionSuccess.value = ''

  if (!theoryForm.value.subtopic_name.trim()) {
    theoryActionError.value = 'Ievadi apakštēmas nosaukumu.'
    return
  }

  if (!selectedTopicId.value) {
    theoryActionError.value = 'Izvēlies tēmu.'
    return
  }

  savingTheory.value = true
  try {
    const payload = {
      subtopic_name: theoryForm.value.subtopic_name.trim(),
    }

    if (editingTheoryId.value) {
      await api.put(`/admin/theories/${editingTheoryId.value}`, payload)
      theoryActionSuccess.value = 'Apakštēma atjaunināta.'
    } else {
      await api.post(`/admin/topics/${selectedTopicId.value}/theories`, payload)
      theoryActionSuccess.value = 'Apakštēma veiksmīgi pievienota.'
    }

    closeTheoryDialog()
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    theoryActionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās saglabāt apakštēmu.'
  } finally {
    savingTheory.value = false
  }
}

const deleteTheory = async (theoryId: number) => {
  theoryActionError.value = ''
  theoryActionSuccess.value = ''

  if (!window.confirm('Vai tiešām dzēst šo apakštēmu?')) {
    return
  }

  deletingTheoryId.value = theoryId
  try {
    await api.delete(`/admin/theories/${theoryId}`)
    theoryActionSuccess.value = 'Apakštēma dzēsta.'
    await loadTopics()
  } catch (err: any) {
    console.error(err)
    theoryActionError.value = err?.response?.status === 403
      ? 'Šī sadaļa pieejama tikai administratoram.'
      : 'Neizdevās dzēst apakštēmu.'
  } finally {
    deletingTheoryId.value = null
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
