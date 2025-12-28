# TemanKonseling

TemanKonseling adalah aplikasi konseling digital berbasis web yang dapat memonitoring emosi siswa dan juga membantu guru bk dalam manajemen konseling. TemanKonseling selain menjadi wadah konseling juga sebagai wadah informasi mengenai kesehatan mental, atau artikel lainnya yang diunggah oleh guru bk.

---

## Struktur Proyek

```
.
├── backend                # Backend API (Laravel)
├── frontend               # Frontend Dashboard (Nuxt)
├── frontend-siswa         # Frontend Siswa (Nuxt)
├── builds                 # Hasil build nuxt
├── dev.sh                 # Script development (Linux / Mac)
├── dev.bat                # Script development (Windows)
├── LICENSE
└── README.md
```

Penjelasan singkat:

* **backend**: Aplikasi Laravel yang menyediakan REST API untuk seluruh kebutuhan sistem.
* **frontend**: Aplikasi Nuxt untuk dashboard admin, guru, dan pihak internal.
* **frontend-siswa**: Aplikasi Nuxt terpisah untuk pengguna siswa.
* **builds**: Direktori output hasil build frontend yang siap dideploy ke server.

---

## Arsitektur Sistem

Arsitektur aplikasi menggunakan pendekatan **decoupled frontend-backend**:

```
[ Frontend Dashboard ]  ─┐
                         ├──> [ Backend API (Laravel) ] ───> Database
[ Frontend Siswa ]      ─┘
```

Karakteristik utama:

* Backend hanya bertugas sebagai API (tidak menyajikan view).
* Frontend dan frontend-siswa berkomunikasi dengan backend melalui HTTP REST API.
* Autentikasi menggunakan token (Laravel Sanctum).
* Build frontend bersifat statis, atau bisa di ubah `ssr: false` di nuxt.config.ts

---

## Teknologi yang Digunakan

### Backend

* PHP 8+
* Laravel
* Laravel Sanctum
* MySQL / PostgreSQL

### Frontend

* Node.js 20+
* Nuxt 4
* Vue 3
* Pinia
* Tailwind CSS

---

## Cara Menjalankan di Local (Development)

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Backend akan berjalan di:

```
http://localhost:8000
```

### Frontend / Frontend Siswa

```bash
cd frontend
npm install
npm run dev
```

```bash
cd frontend-siswa
npm install
npm run dev
```

---

## Build Frontend (Production)

Frontend dan frontend-siswa menggunakan konfigurasi script yang sama.

### Build Dashboard

```bash
cd frontend
npm run build
```

### Build Frontend Siswa

```bash
cd frontend-siswa
npm run build
```

Output build:

* `nuxt build` → `builds/`
* `nuxt generate` → `builds/`

```
builds/
├── teman-konseling-dashboard
└── teman-konseling-siswa
```

Direktori inilah yang digunakan untuk production ke server.

---

## Deployment

### Backend Deployment

1. Upload folder `backend` ke server.
2. Install dependency:

```bash
composer install --no-dev --optimize-autoloader
```

3. Set environment:

```bash
cp .env.example .env
php artisan key:generate
```

4. Jalankan migrasi:

```bash
php artisan migrate --force
```

5. Konfigurasi web server (Apache / Nginx) mengarah ke:

```
backend/public
```

### Frontend Deployment

1. Ambil hasil build dari folder `builds`.
2. Upload ke server (misalnya ke `/var/www`).

---

## Konfigurasi Environment Frontend

Pastikan endpoint API sudah benar pada `nuxt.config.ts` sebelum build:

```ts
runtimeConfig: {
  public: {
    apiBase: 'http://<your-ip-laravel-serve-and-port>/api'
  }
}
```

---

## Dokumentasi API (Backend)

Backend menyediakan REST API dengan versiing (`/v1`). Dokumentasi API mengikuti standar OpenAPI 3.1. atau dapat menggunakan api docs (loca; only) di `http://<your-ip-laravel-serve-and-port>/docs/api`

### Base URL

```
http://<your-ip-laravel-serve-and-port>/api
```

### Authentication

Sebagian besar endpoint membutuhkan token:

```
Authorization: Bearer <token>
```

### Contoh Endpoint

#### Auth

* `POST /v1/auth/user/login`
* `POST /v1/auth/student/login`
* `POST /v1/auth/logout`
* `GET  /v1/auth/user/me`
* `GET  /v1/auth/student/me`

---

## Lisensi

Project ini dilisensikan di bawah ketentuan yang terdapat pada file LICENSE.
