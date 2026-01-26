<template>
  <footer class="site-footer">
    <v-footer class="footer-bar" app padless>
      <v-container class="footer-container">

        <div class="footer-meta">
          <div>© {{ new Date().getFullYear() }} MKursi. Visas tiesības aizsargātas.</div>
          <small>Izveidots Latvijā, domāts skolēniem.</small>
        </div>
      </v-container>
    </v-footer>
  </footer>
</template>

<script setup lang="ts">
</script>

<style scoped>
.site-footer {
  width: 100%;
}

.footer-bar {
  background: #0f172a;
  color: #e2e8f0;
  padding: 18px 0;
}

.footer-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  align-items: center;
  gap: 18px;
}


.footer-meta {
  display: flex;
  flex-direction: column;
  gap: 4px;
  text-align: right;
  font-size: 0.95rem;
}

.footer-meta small {
  color: #cbd5e1;
}

@media (max-width: 768px) {
  .footer-meta {
    text-align: left;
  }
}
</style>
