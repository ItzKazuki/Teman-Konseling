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
* **frontend**: Aplikasi Nuxt untuk dashboard admin, guru, dan pihak internal. (disarankan untuk desktop, tidak cocok untuk mobile phone)
* **frontend-siswa**: Aplikasi Nuxt terpisah untuk pengguna siswa. (disarankan untuk mobile phone, belum responsive)
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

* Backend hanya bertugas sebagai REST API.
* Frontend dan frontend-siswa berkomunikasi dengan backend melalui HTTP REST API.
* Autentikasi menggunakan token (Laravel Sanctum).
* Build frontend bersifat statis, atau bisa di ubah `ssr: false` di nuxt.config.ts

---

## Teknologi yang Digunakan

### Backend

* PHP 8.3+ (DISARANKAN)
* Laravel 12
* Laravel Sanctum
* MySQL / PostgreSQL

### Frontend

* Node.js 24+ (DISARANKAN)
* Nuxt 4
* Pinia
* Tailwind CSS

---

## Cara Menjalankan di Local (Development)

### Backend

```bash
cd backend
composer install
cp .env.example .env # jangan lupa setting user dan password database di file .env
php artisan key:generate
php artisan migrate # gunakan php artisan migrate --seed untuk seed data dummy & juga user awal
php artisan serve
```

Backend akan berjalan di:

```
http://localhost:8000
```

### Frontend / Frontend Siswa

```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

> ubah `NUXT_PUBLIC_API_URL` ke url backend di file .env

```bash
cd frontend-siswa
cp .env.example .env
npm install
npm run dev
```

> ubah `NUXT_PUBLIC_API_URL` ke url backend di file .env

---

## Build Frontend (Production)

Frontend dan frontend-siswa menggunakan konfigurasi script yang sama.

### Build Dashboard

```bash
cd frontend
npm run generate # untuk membuat web statis, cocok untuk cpanel yang ringan
```

### Build Frontend Siswa

```bash
cd frontend-siswa
npm run generate # untuk membuat web statis, cocok untuk cpanel yang ringan
```

Output build:

```
builds/
├── teman-konseling-dashboard # dari folder project 'frontend'
└── teman-konseling-siswa # dari folder project 'frontend-siswa'
```

Direktori inilah yang digunakan untuk production ke server untuk tampilan web nya.

---

## Deployment to Server

### Backend Deployment

1. Upload folder `backend` ke server.
2. Install dependency:

```bash
composer install --no-dev --optimize-autoloader
```

3. Set environment:

```bash
cp .env.example .env # jangan lupa set user dan password database;
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

Pastikan endpoint API sudah benar pada `.env` sebelum build:

```bash
NUXT_PUBLIC_API_URL="http://<yout-backend-already-hosted-domain>:8821" # url api backend
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
