import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import ParMumsView from '@/views/ParMumsView.vue'
import TopicsView from '@/views/TopicsView.vue'
import TopicTheoriesView from '@/views/TopicTheoriesView.vue'
import TheoryView from '@/views/TheoryView.vue'
import ProfileView from '@/views/ProfileView.vue'
import MyQuestionsView from '@/views/MyQuestionsView.vue'
import AdminQuestionsView from '@/views/AdminQuestionsView.vue'
import AdminTaskCreateView from '@/views/AdminTaskCreateView.vue'
import TasksView from '@/views/TasksView.vue'
import { currentUser, fetchCurrentUser } from '@/services/auth'



const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
  },
  {
    path: '/par-mums',
    name: 'par-mums',
    component: ParMumsView,
  },
  {
    path: '/topics',
    name: 'topics',
    component: TopicsView,
  },
  {
    path: '/topics/:id',
    name: 'topic-theories',
    component: TopicTheoriesView,
  },
  {
    path: '/theories/:id',
    name: 'theory',
    component: TheoryView,
  },
  {
    path: '/tasks',
    name: 'tasks',
    component: TasksView,
  },
  {
    path: '/profile',
    name: 'profile',
    component: ProfileView,
  },
  {
    path: '/my-questions',
    name: 'my-questions',
    component: MyQuestionsView,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/admin/questions',
    name: 'admin-questions',
    component: AdminQuestionsView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/admin/tasks/new',
    name: 'admin-task-create',
    component: AdminTaskCreateView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to) => {
  if (!to.meta.requiresAuth) {
    return true
  }

  if (!currentUser.value) {
    await fetchCurrentUser()
  }

  if (!currentUser.value) {
    return { name: 'login' }
  }

  if (to.meta.requiresAdmin && currentUser.value.role !== 'admin') {
    return { name: 'profile' }
  }

  return true
})

export default router
