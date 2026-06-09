# MKursi — Matemātikas mācību lietotne

`MKursi` ir izglītības mērķiem veidota tīmekļa lietotne, kurā lietotāji var apgūt matemātikas tēmas, lasīt teoriju un risināt uzdevumus. Backend ir uzbūvēts ar **Laravel**, frontend ar **Vue 3** un **Vuetify**, datu glabāšanai izmanto **MySQL**.

**Galvenās funkcijas**
- Tēmas un teorijas sadaļas
- Uzdevumi ar risināšanas saskarni
- Lietotāju jautājumu un atbilžu (UserQuestion) apstrāde
- API endpoints frontendam un trešajām pusēm

---

**Satura rādītājs**
- **Projektu pārskats**
- **Prasības**
- **Ātra uzstādīšana**
- **Backend**
- **Frontend**
- **API piemēri**
- **Migrācijas & dati**


---

**Projektu pārskats**
Lietotne strukturēta šādās galvenajās vietās:
- `backend/` — Laravel lietotne (models: `MathematicsPart`, `Topic`, `Theory`, `Task`, `User`, `UserQuestion`).
- `frontend/` — Vue 3 + Vite frontend (komponenti un skati).

**Prasības**
- PHP 8.2+ (vai 8.3 ja pieejams)
- Composer
- Node.js 18+ un npm/yarn
- MySQL 8+ vai cita atbalstīta datubāze
- (Opcija) Docker un Docker Compose

---

**Ātra uzstādīšana**
1. Klonēt repo un iekustināt vidi:

	 - Backend:

		 - Nokopējiet `.env.example` uz `.env` un iestatiet datubāzi.
		 - Instalēt dependences: `composer install`.
		 - Izveidot atslēgu: `php artisan key:generate`.
		 - Palaist migrācijas un seed: `php artisan migrate --seed`.
		 - Palaist serveri: `php artisan serve` vai izmantojiet Docker.

	 - Frontend:

		 - Iet uz `frontend/` un instalēt: `npm install` vai `yarn`.
		 - Palaist dev serveri: `npm run dev` vai `yarn dev`.

---

**Backend**
- Visi Laravel maršruti atrodas `routes/`.
- Galvenie modeļi atrodami `backend/app/Models/`.

- Ja lietojat Docker, palaižiet konteinerus atbilstoši `backend/Dockerfile` vai atbilstošai Docker Compose konfigurācijai, ja tāda ir pievienota.

---

**Frontend**
- Avots: `frontend/src/` — komponenti, skati un servisi.
- API pieprasījumiem tiek izmantots `frontend/src/services/api.ts`.

---

**API piemēri**
- GET `/api/topics` — saraksts ar tēmām
- GET `/api/topics/{id}` — tēmas detaļas (ieskaitot teoriju un uzdevumus)
- GET `/api/tasks` — uzdevumu saraksts
- POST `/api/user-questions` — lietotāja jautājuma iesniegšana

Piezīme: precīzus maršrutus skatiet `backend/routes/api.php`.

---

**Migrācijas & dati**
- Migrācijas atrodas `backend/database/migrations/`.
- Svarīgākie migration faili jau ietver tabulas priekš `mathematics_parts`, `topics`, `theories`, `tasks`, `user_questions` un lietotāju izmaiņas (`add_api_token_to_users_table`).

---

