<template>
  <section class="about-page">
    <header class="hero">
      <p class="eyebrow">Par mums</p>
      <h1>Mācies matemātiku skaidri, soli pa solim</h1>
      <p class="lead">
        MKursi ir platforma, kas palīdz saprast matemātiku caur praktiskiem
        piemēriem, teorijām un uzdevumiem.
      </p>
    </header>

    <div class="grid">
      <article class="card">
        <h2>Ko tu šeit iegūsi</h2>
        <ul>
          <li>Īsas teorijas sadaļas bez liekas informācijas</li>
          <li>Uzdevumi ar skaidru progresu un pašpārbaudi</li>
          <li>Jautājumu forma, lai saņemtu atbildes</li>
        </ul>
      </article>

      
    </div>

    <section class="timeline">
      <h2>Kā viss notiek</h2>
      <div class="steps">
        <div class="step">
          <span>01</span>
          <p>Izvēlies tēmu un izpēti teoriju īsos blokos.</p>
        </div>
        <div class="step">
          <span>02</span>
          <p>Risini uzdevumus un seko progresam.</p>
        </div>
        <div class="step">
          <span>03</span>
          <p>Ja vajag, uzdod jautājumu un saņem palīdzību.</p>
        </div>
      </div>
    </section>
  </section>
</template>

<style scoped>
.about-page {
  display: grid;
  gap: 28px;
}

.hero {
  padding: 30px 26px;
  border-radius: 22px;
  background:
    radial-gradient(circle at 15% 15%, rgba(20, 184, 166, 0.22), transparent 55%),
    radial-gradient(circle at 80% 0%, rgba(251, 191, 36, 0.2), transparent 45%),
    #0f172a;
  color: #f8fafc;
  box-shadow: 0 18px 40px rgba(15, 23, 42, 0.25);
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.3em;
  font-size: 12px;
  margin: 0 0 12px;
  color: rgba(248, 250, 252, 0.7);
}

h1 {
  margin: 0 0 10px;
  font-size: clamp(28px, 4vw, 44px);
}

.lead {
  margin: 0;
  font-size: 16px;
  max-width: 60ch;
  color: rgba(248, 250, 252, 0.85);
}

.grid {
  display: grid;
  gap: 20px;
}

.card {
  background: #ffffff;
  border-radius: 18px;
  padding: 22px;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
}

.card h2 {
  margin-top: 0;
}

.card ul {
  padding-left: 18px;
  margin: 12px 0 0;
  color: #334155;
}

.accent {
  background: linear-gradient(140deg, rgba(20, 184, 166, 0.12), rgba(251, 191, 36, 0.14));
}

.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 12px;
  margin-top: 16px;
}

.stats span {
  display: block;
  font-size: 24px;
  font-weight: 700;
}

.stats small {
  color: #475569;
}

.timeline {
  background: #ffffff;
  border-radius: 18px;
  padding: 22px;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
}

.steps {
  display: grid;
  gap: 16px;
  margin-top: 14px;
}

.step {
  display: grid;
  grid-template-columns: auto 1fr;
  gap: 12px;
  align-items: start;
  background: #f8fafc;
  border-radius: 14px;
  padding: 14px 16px;
}

.step span {
  font-weight: 700;
  color: #0f172a;
}

.step p {
  margin: 0;
  color: #334155;
}

@media (min-width: 900px) {
  .grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
</style>
