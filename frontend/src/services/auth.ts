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
const AUTH_TOKEN_KEY = 'mkursi.auth_token'

const setAuthMarker = (isAuthenticated: boolean) => {
  if (isAuthenticated) {
    localStorage.setItem(AUTH_MARKER_KEY, '1')
  } else {
    localStorage.removeItem(AUTH_MARKER_KEY)
  }
}

export const setAuthToken = (token: string | null) => {
  if (token) {
    localStorage.setItem(AUTH_TOKEN_KEY, token)
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  } else {
    localStorage.removeItem(AUTH_TOKEN_KEY)
    delete api.defaults.headers.common['Authorization']
  }
}

export const getAuthToken = () => localStorage.getItem(AUTH_TOKEN_KEY)

export const hasStoredSession = () => localStorage.getItem(AUTH_MARKER_KEY) === '1'

const initializeCsrf = async () => {
  try {
    // This initializes the session and gets the CSRF cookie
    await api.get('/sanctum/csrf-cookie')
  } catch (error) {
    // Endpoint might not exist, but session cookies should still be set
    console.debug('CSRF initialization:', error instanceof Error ? error.message : 'unknown')
  }
}

export const fetchCurrentUser = async () => {
  const token = getAuthToken()
  if (!token && !hasStoredSession()) {
    currentUser.value = null
    return null
  }

  try {
    // If we have a token, make sure it's set in headers
    if (token) {
      setAuthToken(token)
    }
    
    const { data } = await api.get('/profile')
    currentUser.value = data.user as User
    setAuthMarker(true)
    return currentUser.value
  } catch (error: any) {

    if (error.response?.status === 401 && !token) {
      try {
        const { data } = await api.get('/token')
        if (data.token) {
          setAuthToken(data.token)
          currentUser.value = data.user as User
          setAuthMarker(true)
          return currentUser.value
        }
      } catch (tokenError) {

      }
    }
    
    currentUser.value = null
    setAuthMarker(false)
    setAuthToken(null)
    return null
  }
}

export const login = async (email: string, password: string) => {
  await initializeCsrf()
  const { data } = await api.post('/login', { email, password })
  currentUser.value = data.user as User
  setAuthMarker(true)

  if (data.token) {
    setAuthToken(data.token)
  }
  return currentUser.value
}

export const register = async (email: string, password: string) => {

  await initializeCsrf()
  
  const { data } = await api.post('/register', { email, password })
  currentUser.value = data.user as User
  setAuthMarker(true)

  if (data.token) {
    setAuthToken(data.token)
  }
  
  return currentUser.value
}

export const logout = async () => {
  try {
    await api.post('/logout')
  } finally {
    currentUser.value = null
    setAuthMarker(false)
    setAuthToken(null)
  }
}

export const deleteAccount = async () => {
  try {
    await api.delete('/profile')
  } finally {
    currentUser.value = null
    setAuthMarker(false)
    setAuthToken(null)
  }
}
