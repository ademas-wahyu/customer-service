# Vodeco Customer Service

![Vodeco Customer Service](public/images/customer-support.png)

Aplikasi Customer Service yang dibangun menggunakan Laravel untuk membantu mengelola interaksi dengan pelanggan secara efisien.

## Fitur

- Autentikasi Pengguna (Login, Register)
- Manajemen Profil Pengguna
- Dashboard untuk Customer Service
- Manajemen Role dan Permission

## Prasyarat

Pastikan lingkungan pengembangan Anda memenuhi persyaratan berikut:

- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL, PostgreSQL, atau SQLite)

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek secara lokal:

1.  **Clone repository:**
    ```bash
    git clone <URL_REPOSITORY_ANDA>
    cd customer-service
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Buat file `.env`:**
    Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
    Sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD) di dalam file `.env` Anda.

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan migrasi database:**
    Ini akan membuat tabel-tabel yang diperlukan di database Anda.
    ```bash
    php artisan migrate
    ```

6.  **Jalankan database seeder:**
    Ini akan mengisi tabel database dengan data awal, seperti role dan user.
    ```bash
    php artisan db:seed
    ```

7.  **Install dependensi JavaScript:**
    ```bash
    npm install
    ```

## Pengembangan

Untuk memulai server pengembangan, jalankan perintah berikut. Perintah ini akan menjalankan server PHP, Vite, antrian, dan log secara bersamaan.

```bash
npm run dev
```

- Aplikasi akan tersedia di `http://127.0.0.1:8000`.
- Vite akan memantau perubahan pada file CSS dan JavaScript Anda.

## Menjalankan Tes

Untuk menjalankan rangkaian tes otomatis (PHPUnit), gunakan perintah berikut:

```bash
php artisan test
```

## Deployment

Berikut adalah panduan umum untuk mendeploy aplikasi ke server produksi:

1.  **Clone repository** di server Anda.
2.  **Install dependensi** dengan mode produksi:
    ```bash
    composer install --no-dev --optimize-autoloader
    npm install
    ```
3.  **Buat file `.env`** dan sesuaikan dengan konfigurasi produksi Anda.
4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```
5.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate --force
    ```
6.  **Build aset frontend:**
    ```bash
    npm run build
    ```
7.  **Optimasi Konfigurasi:**
    Cache konfigurasi, route, dan view untuk meningkatkan performa.
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
8.  **Atur Web Server:**
    Konfigurasikan web server Anda (misalnya Nginx atau Apache) untuk mengarahkan root dokumen ke direktori `public` proyek.
9.  **Atur Supervisor:**
    Konfigurasikan Supervisor untuk menjalankan proses antrian (`php artisan queue:work`) secara terus-menerus di latar belakang.

## Kontribusi

Terima kasih atas minat Anda untuk berkontribusi! Untuk menjaga stabilitas dan keteraturan proyek, mohon perhatikan alur kerja berikut:

- **Jangan melakukan *commit* langsung ke branch `main` atau `develop`.**
- Selalu buat **branch baru** dari branch `develop` untuk setiap fitur atau perbaikan yang Anda kerjakan.
- Gunakan nama branch yang deskriptif (misalnya, `feature/login-page` atau `fix/user-bug`).
- Setelah selesai, ajukan *Pull Request* ke branch `develop`.