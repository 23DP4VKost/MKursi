import { ref } from 'vue'
import { api } from './api'

export interface User {
  id: number
  email: string
  username: string
  role?: string
}

export const currentUser = ref<User | null>(null)

export const fetchCurrentUser = async () => {
  try {
    const { data } = await api.get('/profile')
    currentUser.value = data.user as User
  } catch (error: any) {
    currentUser.value = null
  }
}

export const login = async (email: string, password: string) => {
  const { data } = await api.post('/login', { email, password })
  currentUser.value = data.user as User
  return currentUser.value
}

export const logout = async () => {
  try {
    await api.post('/logout')
  } finally {
    currentUser.value = null
  }
}
