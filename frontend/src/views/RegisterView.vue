<template>
  <div class="auth-page">
    <form class="card" @submit.prevent="handleSubmit">
      <h2>Reģistrēties</h2>

      <label>E-pasts</label>
      <input v-model.trim="email" type="email" placeholder="you@gmail.com" />

      <label>Parole</label>
      <input v-model="password" type="password" placeholder="Vismaz 6 rakstzīmes" />

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">Reģistrēts veiksmīgi.</p>

      <button type="submit">Reģistrēties</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { register } from '@/services/auth'

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
    error.value = 'Parolei jābūt vismaz 6 rakstzīmēm'
    return
  }

  try {
    await register(email.value, password.value)
    success.value = true
    email.value = ''
    password.value = ''
    const redirectTo = '/profile'
    router.push(redirectTo)
  } catch (err) {
    error.value = err?.response?.data?.message || 'Reģistrācija neizdevās'
  }
}
</script>