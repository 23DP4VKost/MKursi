<template>
  <div class="home-page">
    <section class="hero-section">
      <v-card class="hero-card" elevation="0">
        <v-row class="align-center" no-gutters>
          <v-col cols="12" md="6" class="hero-copy">
            <p class="eyebrow">Matemātika bez stresa</p>
            <h1>Atrodi pārliecību katrā uzdevumā</h1>
            <p class="lead">
              MKursi apvieno skaidru teoriju, praktiskus uzdevumus un progresu, ko vari
              pārskatīt vienuviet. Viss latviešu valodā, lai mācīšanās būtu dabiska.
            </p>
            <div class="hero-actions">
              <v-btn color="primary" size="large" class="action-btn" to="/kontakti">
                Uzdot jautājumu
              </v-btn>
              <v-btn variant="outlined" size="large" class="action-btn" to="/par-mums">
                Uzzināt vairāk
              </v-btn>
            </div>
            <div class="hero-metrics">
              <div class="metric">
                <div class="metric-number">10+</div>
                <div class="metric-label">uzdevumi ar paskaidrojumiem</div>
              </div>
              <div class="metric">
                <div class="metric-number">24/7</div>
                <div class="metric-label">pieejama platforma</div>
              </div>
              <div class="metric">
                <div class="metric-number">100%</div>
                <div class="metric-label">saturs latviešu valodā</div>
              </div>
            </div>
          </v-col>

          <v-col cols="12" md="6" class="hero-visual">
            <v-card class="visual-card" elevation="6">
              <div class="visual-header">
                <div class="status-dot"></div>
                <span>Darba plūsma</span>
              </div>
              <div class="visual-body">
                <div class="visual-row">
                  <span>Teorija</span>
                  <v-progress-linear model-value="82" rounded color="primary" height="8" />
                </div>
                <div class="visual-row">
                  <span>Uzdevumi</span>
                  <v-progress-linear model-value="64" rounded color="#f97316" height="8" />
                </div>
              </div>
              <div class="visual-footer">
                <div>
                  <p class="foot-title">Tavs progress</p>
                  <p class="foot-sub">Atgriezeniskā saite katram mēģinājumam</p>
                </div>
                <v-avatar color="primary" size="42">
                  <span class="avatar-initials">MK</span>
                </v-avatar>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-card>
    </section>

    <section class="feature-section">
      <div class="section-header">
        <p class="eyebrow">Galvenās priekšrocības</p>
        <h2>Mācību saturs, kas pielāgojas Tev</h2>
        <p class="section-lead">
          No pamatiem līdz sarežģītām tēmām – MKursi palīdz saprast, praktizēt un analizēt rezultātus.
        </p>
      </div>

      <v-row dense>
        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="4">
            <h3>Teorija, kas skaidro</h3>
            <p>Īsi, vizuāli materiāli ar piemēriem un atgādinājumiem, kas palīdz noturēt uzmanību.</p>
          </v-card>
        </v-col>
        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="4">
            <h3>Uzdevumi ar pakāpēm</h3>
            <p>Grūtības līmeņi, kas aug kopā ar Tevi, un uzreiz redzami rezultāti katram mēģinājumam.</p>
          </v-card>
        </v-col>
        <v-col cols="12" md="4">
          <v-card class="feature-card" elevation="4">
            <h3>Progresa pārskati</h3>
            <p>Grafiki un ieteikumi, kas parāda, kur veltīt vairāk laika un kur jau jūties droši.</p>
          </v-card>
        </v-col>
      </v-row>
    </section>

    <section class="steps-section">
      <div class="section-header">
        <p class="eyebrow">Kā tas notiek</p>
        <h2>Trīs soļi līdz pārliecībai</h2>
      </div>
      <v-row dense>
        <v-col cols="12" md="4">
          <div class="step-card">
            <div class="step-number">1</div>
            <div>
              <h3>Izvēlies tēmu</h3>
              <p>Atlasiet tēmu vai klasi, un mēs sagatavosim piemērotu materiālu kopumu.</p>
            </div>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="step-card">
            <div class="step-number">2</div>
            <div>
              <h3>Skaties un mēģini</h3>
              <p>Pārskati teoriju, tad praktizē dažādos uzdevumu līmeņos, lai nostiprinātu zināšanas.</p>
            </div>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="step-card">
            <div class="step-number">3</div>
            <div>
              <h3>Seko progresam</h3>
              <p>Redzi rezultātus un saņem ieteikumus nākamajiem soļiem, lai mācītos pārliecinošāk.</p>
            </div>
          </div>
        </v-col>
      </v-row>
    </section>
  </div>
</template>

<script setup lang="ts">
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  gap: 42px;
}

.hero-section {
  position: relative;
}

.hero-card {
  padding: 32px 28px 28px;
  border-radius: 20px;
  background: linear-gradient(135deg, rgba(13, 96, 216, 0.08), rgba(249, 115, 22, 0.06)),
    #ffffff;
  border: 1px solid #e7ecf5;
}

.hero-copy h1 {
  font-size: clamp(2.2rem, 4vw, 3rem);
  margin: 8px 0 12px;
}

.hero-copy .lead {
  color: #334155;
  font-size: 1.05rem;
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 700;
  font-size: 0.9rem;
  color: #0d60d8;
  margin-bottom: 6px;
}

.hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin: 20px 0 10px;
}

.action-btn {
  text-transform: none;
  font-weight: 700;
}

.hero-metrics {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px;
  margin-top: 22px;
}

.metric {
  padding: 12px 14px;
  border-radius: 12px;
  background: #f8fafc;
  border: 1px solid #e7ecf5;
}

.metric-number {
  font-size: 1.4rem;
  font-weight: 800;
  color: #0d60d8;
}

.metric-label {
  color: #475569;
  font-size: 0.95rem;
}

.hero-visual {
  display: flex;
  justify-content: center;
}

.visual-card {
  width: 100%;
  max-width: 440px;
  border-radius: 18px;
  border: 1px solid #e7ecf5;
  background: #0f172a;
  color: #e2e8f0;
  padding: 22px;
  box-shadow: 0 25px 70px rgba(15, 23, 42, 0.22);
}

.visual-header {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #cbd5e1;
  font-weight: 600;
  margin-bottom: 16px;
}

.status-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #22c55e;
}

.visual-body {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.visual-row {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 12px;
  align-items: center;
  font-weight: 600;
}

.visual-footer {
  margin-top: 18px;
  padding-top: 12px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.foot-title {
  font-weight: 700;
  color: #ffffff;
}

.foot-sub {
  color: #cbd5e1;
  margin-top: 4px;
}

.avatar-initials {
  font-weight: 800;
  color: #ffffff;
}

.feature-section {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.section-header {
  max-width: 720px;
}

.section-lead {
  color: #475569;
  margin-top: 8px;
}

.feature-card {
  padding: 18px;
  border-radius: 16px;
  border: 1px solid #e7ecf5;
  background: #ffffff;
  height: 100%;
}



.feature-card h3 {
  margin-bottom: 6px;
}

.feature-card p {
  color: #475569;
}

.steps-section {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.step-card {
  display: flex;
  gap: 14px;
  padding: 16px;
  border-radius: 14px;
  background: #ffffff;
  border: 1px solid #e7ecf5;
  box-shadow: 0 10px 40px rgba(15, 23, 42, 0.05);
  height: 100%;
}

.step-number {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: #0d60d8;
  color: #ffffff;
  font-weight: 800;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.step-card p {
  color: #475569;
}



@media (max-width: 960px) {
  .hero-card {
    padding: 24px 18px;
  }

  .hero-actions {
    justify-content: flex-start;
  }

  .visual-card {
    max-width: 100%;
  }
}
</style>
