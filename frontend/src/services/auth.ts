import { ref } from 'vue'
import { api } from './api'

export interface User {
  id: number
  email: string
  username: string
  role?: string
}

export const currentUser = ref<User | null>(null)
const AUTH_MARKER_KEY = 'mkursi.authenticated'

const setAuthMarker = (isAuthenticated: boolean) => {
  if (isAuthenticated) {
    localStorage.setItem(AUTH_MARKER_KEY, '1')
  } else {
    localStorage.removeItem(AUTH_MARKER_KEY)
  }
}

export const hasStoredSession = () => localStorage.getItem(AUTH_MARKER_KEY) === '1'

export const fetchCurrentUser = async () => {
  if (!hasStoredSession()) {
    currentUser.value = null
    return null
  }

  try {
    const { data } = await api.get('/profile')
    currentUser.value = data.user as User
    setAuthMarker(true)
    return currentUser.value
  } catch (error: any) {
    currentUser.value = null
    setAuthMarker(false)
    return null
  }
}

export const login = async (email: string, password: string) => {
  const { data } = await api.post('/login', { email, password })
  currentUser.value = data.user as User
  setAuthMarker(true)
  return currentUser.value
}

export const logout = async () => {
  try {
    await api.post('/logout')
  } finally {
    currentUser.value = null
    setAuthMarker(false)
  }
}

export const deleteAccount = async () => {
  try {
    await api.delete('/profile')
  } finally {
    currentUser.value = null
    setAuthMarker(false)
  }
}
