<template>
  <div class="auth-page">
    <form class="card" @submit.prevent="handleSubmit">
      <h2>Login</h2>

      <label>Email</label>
      <input v-model.trim="email" type="email" placeholder="you@example.com" />

      <label>Password</label>
      <input v-model="password" type="password" placeholder="At least 6 characters" />

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">Logged in successfully.</p>

      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')
const error = ref('')
const success = ref(false)

const handleSubmit = async () => {
  error.value = ''
  success.value = false

  if (!email.value.includes('@')) {
    error.value = 'Email must contain @'
    return
  }
  if (password.value.length < 6) {
    error.value = 'Password must be at least 6 characters'
    return
  }

  try {
    const res = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value })
    })

    if (!res.ok) {
      const data = await res.json().catch(() => ({}))
      error.value = data.message || 'Login failed'
      return
    }

    success.value = true
    email.value = ''
    password.value = ''
  } catch {
    error.value = 'Network error'
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  background: #f6f7fb;
}
.card {
  width: 360px;
  background: #fff;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 10px 24px rgba(0,0,0,.08);
  display: grid;
  gap: 10px;
}
label { font-size: 14px; color: #444; }
input {
  padding: 10px 12px;
  border: 1px solid #dcdfe6;
  border-radius: 8px;
  outline: none;
}
button {
  margin-top: 8px;
  padding: 10px 12px;
  background: #4f46e5;
  color: #fff;
  border: 0;
  border-radius: 8px;
  cursor: pointer;
}
.error { color: #e11d48; font-size: 13px; }
.success { color: #16a34a; font-size: 13px; }
</style>