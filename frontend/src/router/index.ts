import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import ContactView from '@/views/ContactView.vue'
import ParMumsView from '@/views/ParMumsView.vue'
import TopicsView from '@/views/TopicsView.vue'
import TopicTheoriesView from '@/views/TopicTheoriesView.vue'
import TheoryView from '@/views/TheoryView.vue'



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
    path: '/kontakti',
    name: 'kontakti',
    component: ContactView,
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
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
