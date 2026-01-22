<template>
  <footer class="site-footer">
    <v-footer color="primary" app padless>
      <v-col class="text-center white--text py-4">
        © {{ new Date().getFullYear() }} MKursi. Visas tiesības aizsargātas.
      </v-col>
    </v-footer>
  </footer>
</template>

<script setup lang="ts">
</script>

<style scoped>
.site-footer {
  width: 100%;
}
</style>
