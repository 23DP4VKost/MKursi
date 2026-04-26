<template>
  <v-container class="py-6">
    <v-row dense>
      <v-col cols="12" lg="4">
        <v-card class="task-panel">
          <v-card-title class="text-h6">Uzdevumi pēc tēmas</v-card-title>
          <v-divider />
          <v-card-text>
            <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

            <v-alert v-else-if="error" type="error" variant="tonal" class="mb-4">
              {{ error }}
            </v-alert>

            <div v-else>
              <div class="mb-4 task-filters">
                <v-text-field
                  v-model="searchQuery"
                  label="Meklēt uzdevumu"
                  prepend-inner-icon="mdi-magnify"
                  variant="outlined"
                  density="compact"
                  hide-details
                  clearable
                />
                <v-select
                  v-model="selectedPartId"
                  :items="partOptions"
                  label="Filtrēt pēc daļas"
                  variant="outlined"
                  density="compact"
                  hide-details
                  clearable
                />
                <v-select
                  v-model="selectedTopicId"
                  :items="topicOptions"
                  label="Filtrēt pēc tēmas"
                  variant="outlined"
                  density="compact"
                  hide-details
                  clearable
                />
                <v-select
                  v-model="selectedDifficulty"
                  :items="difficultyOptions"
                  label="Grūtības līmenis"
                  variant="outlined"
                  density="compact"
                  hide-details
                  clearable
                />
              </div>

              <div class="task-tree">
                <v-expansion-panels variant="accordion" multiple>
                  <v-expansion-panel v-for="part in filteredParts" :key="part.id">
                    <v-expansion-panel-title>
                      <div class="d-flex align-center justify-space-between w-100 gap-3">
                        <span>{{ part.name }}</span>
                        <v-chip size="small" variant="tonal">{{ part.topics.length }} tēmas</v-chip>
                      </div>
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                      <v-expansion-panels variant="accordion">
                        <v-expansion-panel v-for="topic in filteredTopics(part.topics)" :key="topic.id">
                          <v-expansion-panel-title>
                            <div class="d-flex align-center justify-space-between w-100 gap-3">
                              <span>{{ topic.name }}</span>
                              <v-chip size="small" variant="outlined">{{ filteredTasks(topic.tasks).length }} uzdevumi</v-chip>
                            </div>
                          </v-expansion-panel-title>
                          <v-expansion-panel-text>
                            <v-list class="bg-transparent" density="compact">
                              <v-list-item
                                v-for="task in filteredTasks(topic.tasks)"
                                :key="task.id"
                                :active="task.id === selectedTaskId"
                                class="task-list-item"
                                rounded="lg"
                                @click="selectTask(task.id)"
                              >
                                <v-list-item-title>{{ task.name }}</v-list-item-title>
                                <v-list-item-subtitle>
                                  {{ difficultyLabel(task.difficulty_level) }}
                                </v-list-item-subtitle>
                                <template #append>
                                  <v-chip
                                    v-if="taskResults[task.id]"
                                    size="x-small"
                                    :color="taskResults[task.id].correct ? 'success' : 'error'"
                                    variant="flat"
                                  >
                                    {{ taskResults[task.id].correct ? 'Pabeigts' : 'Neprecīzi' }}
                                  </v-chip>
                                </template>
                              </v-list-item>
                            </v-list>
                          </v-expansion-panel-text>
                        </v-expansion-panel>
                      </v-expansion-panels>
                    </v-expansion-panel-text>
                  </v-expansion-panel>
                </v-expansion-panels>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" lg="8">
        <v-card class="task-workspace">
          <v-card-title class="d-flex align-center justify-space-between flex-wrap gap-3">
            <span class="text-h6">{{ selectedTask ? selectedTask.name : 'Izvēlies uzdevumu' }}</span>
            <v-chip v-if="selectedTask" color="primary" variant="tonal">
              {{ taskPathLabel }}
            </v-chip>
          </v-card-title>
          <v-divider />
          <v-card-text>
            <template v-if="selectedTask">
              <div class="task-meta mb-4">
                <v-chip size="small" variant="outlined">{{ currentTopicName }}</v-chip>
                <v-chip size="small" variant="outlined">{{ currentPartName }}</v-chip>
                <v-chip size="small" variant="outlined">{{ difficultyLabel(selectedTask.difficulty_level) }}</v-chip>
              </div>

              <div class="task-question mb-6">
                {{ selectedTask.question }}
              </div>

              <v-alert
                v-if="taskResults[selectedTask.id]"
                :type="taskResults[selectedTask.id].correct ? 'success' : 'error'"
                variant="tonal"
                class="mb-4"
              >
                {{ taskResults[selectedTask.id].message }}
              </v-alert>

              <v-text-field
                v-model="currentAnswer"
                label="Tava atbilde"
                variant="outlined"
                class="mb-4"
                @keyup.enter="checkAnswer"
              />

              <div class="d-flex flex-wrap ga-3">
                <v-btn color="primary" :disabled="selectedTaskIndex === 0" @click="goPrevious">
                  Atpakaļ
                </v-btn>
                <v-btn color="primary" variant="tonal" :disabled="selectedTaskIndex === filteredFlattenedTasks.length - 1" @click="goNext">
                  Tālāk
                </v-btn>
                <v-spacer />
                <v-btn color="success" @click="checkAnswer">
                  Pārbaudīt atbildi
                </v-btn>
              </div>

              <v-alert v-if="selectedTask.correct_answer && showCorrectAnswer" type="info" variant="tonal" class="mt-4">
                Pareizā atbilde: {{ selectedTask.correct_answer }}
              </v-alert>
            </template>

            <v-alert v-else type="info" variant="tonal">
              Spied uz kāda uzdevuma kreisajā pusē, lai sāktu risināt.
            </v-alert>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { api } from '@/services/api'

interface TaskItem {
  id: number
  name: string
  question: string
  correct_answer: string | null
  difficulty_level: 'viegls' | 'videjs' | 'sarezgit' | null
  topic_id: number
}

interface TopicItem {
  id: number
  name: string
  tasks: TaskItem[]
}

interface PartItem {
  id: number
  code: string
  name: string
  topics: TopicItem[]
}

interface SelectOption {
  title: string
  value: number | string
}

const parts = ref<PartItem[]>([])
const loading = ref(true)
const error = ref('')
const searchQuery = ref('')
const selectedPartId = ref<number | null>(null)
const selectedTopicId = ref<number | null>(null)
const selectedDifficulty = ref<'viegls' | 'videjs' | 'sarezgit' | null>(null)
const selectedTaskId = ref<number | null>(null)
const taskAnswers = ref<Record<number, string>>({})
const taskResults = ref<Record<number, { correct: boolean; message: string }>>({})

const difficultyOptions: SelectOption[] = [
  { title: 'Viegls', value: 'viegls' },
  { title: 'Vidējs', value: 'videjs' },
  { title: 'Sarežģīts', value: 'sarezgit' },
]

const partOptions = computed<SelectOption[]>(() => [
  { title: 'Visas daļas', value: '' },
  ...parts.value.map((part) => ({ title: part.name, value: part.id })),
])

const topicOptions = computed<SelectOption[]>(() => {
  const topics = parts.value.flatMap((part) => part.topics)
  const uniqueTopics = topics.filter((topic, index, list) => list.findIndex((item) => item.id === topic.id) === index)

  return [
    { title: 'Visas tēmas', value: '' },
    ...uniqueTopics.map((topic) => ({ title: topic.name, value: topic.id })),
  ]
})

const flattenedTasks = computed(() =>
  parts.value.flatMap((part) =>
    part.topics.flatMap((topic) => topic.tasks.map((task) => ({
      ...task,
      partId: part.id,
      topicName: topic.name,
      partName: part.name,
    }))),
  ),
)

const filteredFlattenedTasks = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()

  return flattenedTasks.value.filter((task) => {
    const matchesPart = !selectedPartId.value || task.partId === selectedPartId.value
    const matchesTopic = !selectedTopicId.value || task.topic_id === selectedTopicId.value
    const matchesDifficulty = !selectedDifficulty.value || task.difficulty_level === selectedDifficulty.value
    const matchesQuery = !query || [task.name, task.question, task.partName, task.topicName].some((value) =>
      value.toLowerCase().includes(query),
    )

    return matchesPart && matchesTopic && matchesDifficulty && matchesQuery
  })
})

const filteredParts = computed(() => {
  const selectedTasks = new Set(filteredFlattenedTasks.value.map((task) => task.id))

  return parts.value
    .map((part) => ({
      ...part,
      topics: part.topics
        .map((topic) => ({
          ...topic,
          tasks: topic.tasks.filter((task) => selectedTasks.has(task.id)),
        }))
        .filter((topic) => topic.tasks.length > 0),
    }))
    .filter((part) => part.topics.length > 0)
})

const filteredTopics = (topics: TopicItem[]) => {
  const selectedTasks = new Set(filteredFlattenedTasks.value.map((task) => task.id))

  return topics.filter((topic) => topic.tasks.some((task) => selectedTasks.has(task.id)))
}

const filteredTasks = (tasks: TaskItem[]) => {
  const selectedTasks = new Set(filteredFlattenedTasks.value.map((task) => task.id))

  return tasks.filter((task) => selectedTasks.has(task.id))
}

const selectedTask = computed(() =>
  filteredFlattenedTasks.value.find((task) => task.id === selectedTaskId.value) ?? null,
)

const selectedTaskIndex = computed(() =>
  selectedTask.value ? filteredFlattenedTasks.value.findIndex((task) => task.id === selectedTask.value?.id) : -1,
)

const currentPartName = computed(() => selectedTask.value?.partName ?? '')
const currentTopicName = computed(() => selectedTask.value?.topicName ?? '')
const taskPathLabel = computed(() => [currentPartName.value, currentTopicName.value].filter(Boolean).join(' · '))
const showCorrectAnswer = ref(false)

const currentAnswer = computed({
  get: () => {
    if (!selectedTaskId.value) {
      return ''
    }

    return taskAnswers.value[selectedTaskId.value] ?? ''
  },
  set: (value: string) => {
    if (!selectedTaskId.value) {
      return
    }

    taskAnswers.value[selectedTaskId.value] = value
    showCorrectAnswer.value = false
  },
})

const difficultyLabel = (value: TaskItem['difficulty_level']) => {
  switch (value) {
    case 'viegls':
      return 'Viegls'
    case 'videjs':
      return 'Vidējs'
    case 'sarezgit':
      return 'Sarežģīts'
    default:
      return 'Nav norādīts'
  }
}

const loadTasks = async () => {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get<PartItem[]>('/tasks')
    parts.value = data

    selectedTaskId.value = filteredFlattenedTasks.value[0]?.id ?? null
  } catch (err) {
    console.error(err)
    error.value = 'Neizdevās ielādēt uzdevumus.'
  } finally {
    loading.value = false
  }
}

const selectTask = (taskId: number) => {
  selectedTaskId.value = taskId
  showCorrectAnswer.value = false
}

const goPrevious = () => {
  if (selectedTaskIndex.value > 0) {
    selectTask(filteredFlattenedTasks.value[selectedTaskIndex.value - 1].id)
  }
}

const goNext = () => {
  if (selectedTaskIndex.value >= 0 && selectedTaskIndex.value < filteredFlattenedTasks.value.length - 1) {
    selectTask(filteredFlattenedTasks.value[selectedTaskIndex.value + 1].id)
  }
}

const checkAnswer = () => {
  if (!selectedTask.value) {
    return
  }

  const expected = selectedTask.value.correct_answer?.trim()

  if (!expected) {
    taskResults.value[selectedTask.value.id] = {
      correct: false,
      message: 'Šim uzdevumam nav norādīta pareizā atbilde.',
    }
    showCorrectAnswer.value = true
    return
  }

  const submitted = (taskAnswers.value[selectedTask.value.id] ?? '').trim()
  const correct = submitted.toLowerCase() === expected.toLowerCase()

  taskResults.value[selectedTask.value.id] = {
    correct,
    message: correct ? 'Pareizi. Vari doties tālāk.' : 'Vēl ne gluži. Pamēģini vēlreiz.',
  }
  showCorrectAnswer.value = !correct
}

onMounted(loadTasks)

watch([selectedPartId, selectedTopicId, selectedDifficulty, searchQuery], () => {
  if (selectedTaskId.value && filteredFlattenedTasks.value.some((task) => task.id === selectedTaskId.value)) {
    return
  }

  selectedTaskId.value = filteredFlattenedTasks.value[0]?.id ?? null
}, { immediate: true })
</script>

<style scoped>
.task-panel,
.task-workspace {
  border-radius: 24px;
  overflow: hidden;
}

.task-tree {
  max-height: calc(100vh - 220px);
  overflow: auto;
  padding-right: 4px;
}

.task-filters {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.task-list-item {
  margin-bottom: 8px;
  border: 1px solid rgba(15, 23, 42, 0.08);
}

.task-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.task-question {
  font-size: 1.05rem;
  line-height: 1.7;
  white-space: pre-line;
  color: rgb(15, 23, 42);
}
</style>