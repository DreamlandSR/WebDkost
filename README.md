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
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

> ⚠️ Gunakan `DB_HOST=db` bukan `DB_HOST=127.0.0.1`

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

### 7 — Jalankan Migration dan Seeder

```bash
docker exec laravel_app php artisan migrate --seed
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

# Restart container tertentu
docker compose restart laravel_nginx
```

### Artisan Commands

```bash
# Migration
docker exec laravel_app php artisan migrate
docker exec laravel_app php artisan migrate:rollback
docker exec laravel_app php artisan db:seed

# Clear cache
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan view:clear
```

### Menjalankan Test

```bash
# Test di lokal (wajib sebelum push!)
php artisan test

# Test spesifik
php artisan test tests/Feature/ProfileTest.php

# Test dengan detail
php artisan test --verbose
```

### Melihat Log

```bash
docker logs laravel_app --tail 50
docker logs laravel_nginx --tail 50
docker logs laravel_db --tail 50
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
php artisan test

# 5. Commit dan push
git add .
git commit -m "feat: deskripsi perubahan"
git push origin feature/nama-fitur

# 6. Buat Pull Request ke develop di GitHub
# 7. Minta review dari anggota tim
# 8. Setelah approve, merge ke develop
# 9. Develop ke main melalui PR
```

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
Deploy Dev  → docker compose up (branch develop)
Deploy Prod → rsync ke server (branch main)
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

### 4. Jenkins permission denied Docker socket

```bash
# Fix manual (lakukan setiap WSL restart jika .bashrc belum dikonfigurasi)
sudo chmod 666 /run/docker.sock

# Fix permanent
echo 'sudo chmod 666 /run/docker.sock 2>/dev/null || true' >> ~/.bashrc
source ~/.bashrc
```

### 5. Git permission error saat checkout branch

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
git config core.fileMode false
git restore storage/
git restore bootstrap/cache/
```

### 6. VSCode tidak sync dengan perubahan di WSL

```bash
# Selalu buka VSCode dari terminal WSL
cd /var/www/html/WebDkost
code .
# Pastikan pojok kiri bawah menunjukkan ">< WSL: Ubuntu"
```

### 7. File not found saat akses localhost:8000

```bash
# Pastikan nginx container jalan
docker ps | grep laravel_nginx

# Jika tidak jalan, start ulang
docker compose up -d

# Cek log nginx
docker logs laravel_nginx --tail 20
```

### 8. php artisan serve tidak bisa konek database

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
