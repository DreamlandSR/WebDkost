# WebDkost 🏠

Aplikasi manajemen kost berbasis web menggunakan Laravel 11, Docker, dan Jenkins CI/CD.

---

## 📋 Persyaratan

Pastikan sudah terinstall di komputer kamu:

| Tools | Versi | Link |
|---|---|---|
| Git | Latest | https://git-scm.com |
| Docker Desktop | Latest | https://www.docker.com/products/docker-desktop |
| WSL2 (Windows) | Ubuntu | https://learn.microsoft.com/en-us/windows/wsl |
| VSCode | Latest | https://code.visualstudio.com |
| VSCode Extension | WSL | Install dari VSCode Marketplace |

---

## 🚀 Langkah Setup dari Awal

### 1 — Clone Repository

Buka terminal WSL, lalu:

```bash
cd /var/www/html
git clone https://github.com/DreamlandSR/WebDkost.git
cd WebDkost
```

### 2 — Buka dengan VSCode

```bash
code .
```

> ⚠️ Pastikan VSCode terbuka dengan mode **WSL: Ubuntu** (terlihat di pojok kiri bawah VSCode).
> Jika tidak, tekan `Ctrl+Shift+P` → ketik `WSL: Connect to WSL`

### 3 — Salin File Environment

```bash
cp .env.example .env
nano .env
```

Pastikan bagian database seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=username
DB_PASSWORD=password

SESSION_DRIVER=file
CACHE_STORE=file
```

> ⚠️ Gunakan `DB_HOST=db` bukan `DB_HOST=127.0.0.1`
> ⚠️ Gunakan `SESSION_DRIVER=file` dan `CACHE_STORE=file` untuk menghindari error tabel sessions/cache

### 4 — Setup Docker Socket (WSL)

Agar Jenkins bisa akses Docker, lakukan ini sekali saja:

```bash
# Tambahkan NOPASSWD di sudoers
sudo visudo
```

Tambahkan baris ini di paling bawah:
```
YOUR_USERNAME ALL=(ALL) NOPASSWD: /bin/chmod 666 /run/docker.sock
```

```bash
# Tambahkan ke .bashrc agar otomatis setiap buka terminal
echo 'sudo chmod 666 /run/docker.sock 2>/dev/null || true' >> ~/.bashrc
source ~/.bashrc
```

### 5 — Build dan Jalankan Docker

```bash
cd /var/www/html/WebDkost
docker compose up -d --build
```

Cek statusnya:

```bash
docker ps
```

Pastikan semua container berstatus **Up**:

```
laravel_nginx  ✅
laravel_app    ✅
laravel_db     ✅
```

### 6 — Generate Application Key

```bash
docker exec laravel_app php artisan key:generate
```

### 7 — Jalankan Migration

```bash
docker exec laravel_app php artisan migrate --force
```

### 8 — Akses Aplikasi

```
http://localhost:8000
```

---

## 🗄️ Akses Database (phpMyAdmin)

```
http://localhost:9500
```

---

## 🔧 Perintah Berguna

### Container

```bash
# Jalankan semua container
docker compose up -d

# Stop semua container
docker compose down

# Stop dan hapus semua volume (HATI-HATI: data akan hilang)
docker compose down -v

# Rebuild image dari awal
docker compose down
docker rmi webdkost-app -f
docker compose build --no-cache
docker compose up -d

# Restart container tertentu
docker compose restart nginx
```

### Artisan Commands

```bash
# Migration
docker exec laravel_app php artisan migrate --force
docker exec laravel_app php artisan migrate:rollback
docker exec laravel_app php artisan migrate:fresh --force

# Clear cache
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan view:clear
docker exec laravel_app php artisan route:clear

# Cek status tabel database
docker exec laravel_app php artisan migrate:status
docker exec laravel_app php artisan db:show
```

### Melihat Log

```bash
docker logs laravel_app --tail 50
docker logs laravel_nginx --tail 50
docker logs laravel_db --tail 50
```

### Menjalankan Test

```bash
# Test di lokal (wajib sebelum push!)
docker exec laravel_app php artisan test

# Test spesifik
docker exec laravel_app php artisan test tests/Feature/ProfileTest.php

# Test dengan detail
docker exec laravel_app php artisan test --verbose
```

---

## 🌿 Git Workflow

### Alur Branch

```
main           → Production (protected, butuh review)
develop        → Staging/Testing
fix/branch     → Perbaikan bug
feature/branch → Fitur baru
```

### Alur Kerja Harian

```bash
# 1. Mulai dari develop terbaru
git checkout develop
git pull origin develop

# 2. Buat branch baru
git checkout -b feature/nama-fitur

# 3. Kerjakan perubahan...

# 4. Test di lokal WAJIB sebelum push
docker exec laravel_app php artisan test

# 5. Commit dan push
git add .
git commit -m "feat: deskripsi perubahan"
git push origin feature/nama-fitur

# 6. Buat Pull Request ke develop di GitHub
# 7. Minta review dari anggota tim
# 8. Setelah approve, merge ke develop
# 9. Develop ke main melalui PR
```

### ⚠️ Penting Setelah Merge Branch

Setelah merge branch ke develop, selalu rebuild Docker agar perubahan masuk ke container:

```bash
git checkout develop
git pull origin develop

docker compose down
docker rmi webdkost-app -f
docker compose build --no-cache
docker compose up -d

sleep 5
docker exec laravel_app php artisan migrate --force
```

> ⚠️ Karena file di-COPY ke dalam Docker image saat build, setiap perubahan kode memerlukan rebuild image.

### Format Pesan Commit

```
feat     : tambah fitur baru
fix      : perbaiki bug
chore    : update dependencies
docs     : update dokumentasi
test     : tambah/update test
refactor : refactor kode
```

### Aturan Branch Protection

- Branch `main` dilindungi — tidak bisa push langsung
- Wajib buat Pull Request untuk merge ke `main`
- Minimal 1 approval dari anggota tim (kecuali owner)
- Owner bisa merge tanpa approval

> ⚠️ **Selalu test lokal dulu sebelum push** — jangan sampai Jenkins gagal karena kode belum ditest

---

## 🔄 CI/CD dengan Jenkins

### Stage Pipeline

```
Build → Build Frontend → Testing → Deploy Dev → Deploy Prod
```

### Akses Jenkins

```
http://localhost:8080
```

### Cara Kerja

```
Push ke GitHub
      ↓
Jenkins otomatis trigger (jika webhook aktif)
atau Build Now manual
      ↓
Build  → Install PHP dependencies
      ↓
Build Frontend → npm install & build Vite
      ↓
Testing → php artisan test (SQLite)
      ↓
Deploy Dev  → docker compose up (branch develop/main)
Deploy Prod → konfigurasi server production
```

### Menjalankan Build Manual

1. Buka `http://localhost:8080`
2. Klik job **laravel-dev**
3. Klik **Build Now**
4. Klik **Open Blue Ocean** untuk melihat progress

---

## 📁 Struktur Project

```
WebDkost/
├── app/
│   ├── Http/Controllers/   → Controller
│   ├── Models/             → Model Eloquent
│   │   ├── Kamar.php
│   │   ├── GaleriKamar.php
│   │   ├── Booking.php
│   │   ├── Tagihan.php
│   │   ├── Pembayaran.php
│   │   ├── FasilitasKamar.php
│   │   ├── Furnitur.php
│   │   ├── Keluhan.php
│   │   ├── Review.php
│   │   └── User.php
│   └── Providers/          → Service Provider
├── database/
│   ├── factories/          → Factory untuk testing
│   ├── migrations/         → Database migration
│   └── seeders/            → Database seeder
├── docker/
│   └── nginx/
│       └── default.conf    → Konfigurasi Nginx
├── public/                 → Entry point aplikasi
├── resources/
│   ├── js/                 → JavaScript/Vite
│   └── views/              → Blade templates
├── routes/
│   └── web.php             → Definisi route
├── tests/
│   ├── Feature/            → Feature test
│   └── Unit/               → Unit test
├── .env.example            → Template environment
├── docker-compose.yml      → Docker orchestration
├── Dockerfile              → Docker image config
├── entrypoint.sh           → Docker entrypoint script
└── Jenkinsfile             → CI/CD pipeline config
```

---

## 🗃️ Struktur Database

| Tabel | Keterangan |
|---|---|
| `users` | Data pengguna (admin & penyewa) |
| `kamar` | Data kamar kost |
| `galeri_kamar` | Foto-foto kamar |
| `fasilitas_kamar` | Fasilitas tiap kamar |
| `furnitur` | Data furnitur tambahan |
| `booking` | Data pemesanan kamar |
| `booking_detail_furnitur` | Furnitur yang dipilih per booking |
| `tagihan` | Tagihan bulanan penyewa |
| `pembayaran` | Riwayat pembayaran |
| `pendapatan` | Rekap pendapatan |
| `pengeluaran` | Rekap pengeluaran |
| `review` | Ulasan kamar dari penyewa |
| `keluhan` | Keluhan penyewa |
| `sessions` | Session Laravel |
| `cache` | Cache Laravel |

---

## ❗ Troubleshooting

### 1. Container tidak mau jalan

```bash
docker compose down
docker rmi webdkost-app -f
docker compose up -d --build
```

### 2. Permission denied di storage

```bash
docker exec laravel_app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec laravel_app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### 3. Database tidak konek

```bash
# Pastikan DB_HOST=db di .env, bukan 127.0.0.1
docker ps | grep laravel_db
docker compose up -d laravel_db
docker logs laravel_db --tail 30
```

### 4. File not found di localhost:8000

Penyebab paling umum di WSL2 — volume mount tidak bekerja dengan relative path.

```bash
# Pastikan docker-compose.yml pakai absolute path
# volumes:
#   - /var/www/html/WebDkost:/var/www   ✅
#   - .:/var/www                         ❌ (tidak bekerja di WSL2)

# Rebuild ulang
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### 5. Error: Table 'laravel.sessions' doesn't exist

```bash
docker exec laravel_app php artisan session:table
docker exec laravel_app php artisan migrate --force
```

Atau ganti ke file driver di `.env`:
```env
SESSION_DRIVER=file
CACHE_STORE=file
```

### 6. Error: Table 'laravel.products' doesn't exist

Project ini menggunakan tabel `kamar`, bukan `products`. Pastikan semua Controller, Model, dan View sudah menggunakan model `Kamar` bukan `Product`.

```bash
# Cari file yang masih pakai 'products'
docker exec laravel_app grep -r "products" /var/www/app --include="*.php" -l
```

### 7. Perubahan kode tidak muncul setelah pull

Karena file di-COPY ke dalam Docker image, perlu rebuild setiap ada perubahan:

```bash
docker compose down
docker rmi webdkost-app -f
docker compose build --no-cache
docker compose up -d
sleep 5
docker exec laravel_app php artisan migrate --force
```

### 8. Error: composer.json not found di container

Volume mount tidak bekerja. Pastikan menjalankan `docker compose` dari direktori project yang benar:

```bash
cd /var/www/html/WebDkost
docker compose up -d
```

### 9. Jenkins permission denied Docker socket

```bash
# Fix manual (lakukan setiap WSL restart jika .bashrc belum dikonfigurasi)
sudo chmod 666 /run/docker.sock

# Fix permanent
echo 'sudo chmod 666 /run/docker.sock 2>/dev/null || true' >> ~/.bashrc
source ~/.bashrc
```

### 10. PHP version conflict saat build

Jika muncul error seperti `requires php-64bit ^8.3`, ganti versi PHP di Dockerfile:

```dockerfile
FROM php:8.3-fpm   # ✅ Gunakan 8.3
```

### 11. Git permission error saat checkout branch

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
git config core.fileMode false
git restore storage/
git restore bootstrap/cache/
```

### 12. VSCode tidak sync dengan perubahan di WSL

```bash
# Selalu buka VSCode dari terminal WSL
cd /var/www/html/WebDkost
code .
# Pastikan pojok kiri bawah menunjukkan ">< WSL: Ubuntu"
```

### 13. php artisan serve tidak bisa konek database

Jangan gunakan `php artisan serve` — gunakan Docker:
```bash
# Stop artisan serve (Ctrl+C)
# Gunakan Docker sebagai gantinya
docker compose up -d
# Akses via http://localhost:8000
```

---

## 👥 Tim

| Nama | Role | GitHub |
|---|---|---|
| DreamlandSR | Owner/Backend | @DreamlandSR |

---

## 📝 Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).
