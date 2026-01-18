<template>
  <v-container fluid class="pa-0">
    <!-- HERO -->
    <v-parallax
      src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=80"
      height="520"
    >
      <div class="hero-overlay">
        <h1 class="hero-title">MKursi</h1>
        <p class="hero-subtitle">
          Mūsdienīga platforma matemātikas izpratnei un zināšanu nostiprināšanai
        </p>
        <v-btn
          color="primary"
          size="x-large"
          class="mt-6"
          rounded
        >
          Sākt mācīties
        </v-btn>
      </div>
    </v-parallax>

    <!-- FEATURES -->
    <v-container class="my-14">
      <v-row justify="center" class="mb-10">
        <h2 class="section-title">Kā mēs palīdzam Tev mācīties</h2>
      </v-row>

      <v-row>
        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="6">
            <v-img
              src="https://whs-blogs.co.uk/teaching/wp-content/uploads/2020/02/19413-2000x1200.jpg"
              height="220"
              cover
            />
            <v-card-title>Teorija bez sarežģījumiem</v-card-title>
            <v-card-text>
              Strukturēti skaidrojumi ar piemēriem, kas palīdz ātri saprast
              sarežģītas matemātikas tēmas.
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="6">
            <v-img
              src="https://images.unsplash.com/photo-1518133910546-b6c2fb7d79e3?auto=format&fit=crop&w=800&q=80"
              height="220"
              cover
            />
            <v-card-title>Praktiskie uzdevumi</v-card-title>
            <v-card-text>
              Uzdevumi dažādos grūtības līmeņos, lai nostiprinātu zināšanas
              un sagatavotos pārbaudes darbiem.
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="6">
            <v-img
              src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=800&q=80"
              height="220"
              cover
            />
            <v-card-title>Progresa izsekošana</v-card-title>
            <v-card-text>
              Redzi savus rezultātus, analizē kļūdas un koncentrējies uz tēmām,
              kuras jāuzlabo.
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <!-- CTA -->
    <v-container fluid class="cta-section">
      <v-row justify="center" align="center">
        <v-col cols="12" md="9" class="text-center">
          <h2 class="cta-title">
            Mācies gudrāk, nevis grūtāk
          </h2>
          <p class="cta-text">
            Pievienojies platformai un uzlabo savas matemātikas zināšanas jau šodien
          </p>
          <v-btn
            color="secondary"
            size="x-large"
            rounded
            class="mt-6"
          >
            Reģistrēties
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-container>
</template>

<script setup lang="ts">
</script>

<style scoped>
.hero-overlay {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  color: white;
  background: linear-gradient(
    rgba(0, 0, 0, 0.55),
    rgba(0, 0, 0, 0.55)
  );
  padding: 24px;
}

.hero-title {
  font-size: clamp(2.2rem, 5vw, 3.4rem);
  font-weight: 800;
}

.hero-subtitle {
  font-size: 1.3rem;
  max-width: 720px;
  margin-top: 12px;
}

.section-title {
  font-size: 2.2rem;
  font-weight: 700;
  text-align: center;
}

.feature-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.cta-section {
  background: linear-gradient(135deg, #1976d2, #42a5f5);
  color: white;
  padding: 80px 16px;
}

.cta-title {
  font-size: 2.3rem;
  font-weight: 700;
}

.cta-text {
  font-size: 1.2rem;
  margin-top: 12px;
}
</style>
