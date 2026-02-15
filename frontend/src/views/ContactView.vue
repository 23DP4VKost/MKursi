<template>
  <section class="contact-page">
    <div class="hero">
      <p class="eyebrow">Kontakti</p>
      <h1>Uzdod savu jautājumu</h1>
      <p class="lead">
        Aizpildi formu, un mēs atbildēsim cik ātrāk ka iespējams.
      </p>
    </div>

    <form class="card" @submit.prevent="handleSubmit">
      <label>Vards</label>
      <input v-model.trim="name" type="text" placeholder="Tavs vards" />

      <label>Email</label>
      <input v-model.trim="email" type="email" placeholder="you@example.com" />

      <label>Temats</label>
      <input v-model.trim="subject" type="text" placeholder="Par ko ir jautajums?" />

      <label>Jautajums</label>
      <textarea v-model.trim="message" rows="5" placeholder="Raksti savu jautajumu..."></textarea>

      <p v-if="error" class="error">{{ error }}</p>
      <p v-if="success" class="success">Paldies! Jautajums nosutits.</p>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Suta...' : 'Nosutit' }}
      </button>
    </form>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const name = ref('')
const email = ref('')
const subject = ref('')
const message = ref('')
const error = ref('')
const success = ref(false)
const loading = ref(false)

const handleSubmit = async () => {
  error.value = ''
  success.value = false

  if (name.value.length < 2) {
    error.value = 'Vards ir par isuu'
    return
  }
  if (!email.value.includes('@')) {
    error.value = 'Email nav pareizs'
    return
  }
  if (message.value.length < 10) {
    error.value = 'Jautajumam jabut vismaz 10 simboli'
    return
  }

  loading.value = true

  try {
    const res = await fetch('http://localhost:8000/api/kontakti', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        subject: subject.value || null,
        message: message.value,
      }),
    })

    if (!res.ok) {
      const data = await res.json().catch(() => ({}))
      error.value = data.message || 'Neizdevas nosutit jautajumu'
      return
    }

    success.value = true
    name.value = ''
    email.value = ''
    subject.value = ''
    message.value = ''
  } catch {
    error.value = 'Tika konstateta tikla kluda'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.contact-page {
  display: grid;
  gap: 24px;
  grid-template-columns: minmax(0, 1fr);
}

.hero {
  padding: 28px 34px;
  border-radius: 20px;
  background:
    radial-gradient(circle at top left, rgba(79, 70, 229, 0.18), transparent 55%),
    linear-gradient(120deg, rgba(15, 23, 42, 0.08), rgba(255, 255, 255, 0.3));
  box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.2em;
  font-size: 12px;
  margin: 0 0 10px;
  color: #334155;
}

h1 {
  margin: 0 0 8px;
  font-size: clamp(28px, 4vw, 40px);
}

.lead {
  margin: 0;
  color: #475569;
  max-width: 48ch;
}

.card {
  background: #ffffff;
  border-radius: 18px;
  padding: 24px;
  box-shadow: 0 14px 32px rgba(15, 23, 42, 0.1);
  display: grid;
  gap: 12px;
}

label {
  font-size: 14px;
  color: #334155;
}

input,
textarea {
  padding: 12px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  font-size: 15px;
  background: #f8fafc;
  outline: none;
}

input:focus,
textarea:focus {
  border-color: #4f46e5;
  background: #ffffff;
}

button {
  margin-top: 6px;
  padding: 12px 16px;
  border: 0;
  border-radius: 12px;
  background: #0f172a;
  color: #fff;
  font-weight: 600;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

button:disabled {
  cursor: not-allowed;
  opacity: 0.7;
  transform: none;
}

button:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 10px 20px rgba(15, 23, 42, 0.18);
}

.error {
  color: #e11d48;
  font-size: 13px;
}

.success {
  color: #16a34a;
  font-size: 13px;
}

@media (min-width: 900px) {
  .contact-page {
    grid-template-columns: 0.8fr 1fr;
    align-items: start;
  }

  .card {
    max-width: 520px;
    justify-self: end;
  }
}
</style>
