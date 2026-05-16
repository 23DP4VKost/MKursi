<template>
  <div class="auth-page">
    <form class="card" @submit.prevent="handleSubmit">
      <h2>Pieteikšanās</h2>

      <label>Email</label>
      <input v-model.trim="email" type="email" placeholder="you@gmail.com" />

      <label>Parole</label>
      <input v-model="password" type="password" placeholder="Vismaz 6 rakstzīmes" />

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">Ielogojies veiksmīgi.</p>

      <button type="submit">Pieteikšanās</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login } from '../services/auth'

const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')
const success = ref(false)

const handleSubmit = async () => {
  error.value = ''
  success.value = false

  if (!email.value.includes('@')) {
    error.value = 'E-pasta adresē jābūt @ simbolam.'
    return
  }
  if (password.value.length < 6) {
    error.value = 'Parolei jābūt vismaz 6 rakstzīmēm.'
    return
  }

  try {
    await login(email.value, password.value)
    success.value = true
    const redirectTo = '/profile'
    router.push(redirectTo)
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Pieteikšanās neizdevās'
  }
}
</script>