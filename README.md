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

> ⚠️ Pastikan VSCode terbuka dengan mode **WSL: Ubuntu** (terlihat di pojok kiri bawah VSCode)

### 3 — Salin File Environment

```bash
cp .env.example .env
```

Lalu edit file `.env` sesuai kebutuhan:

```bash
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

### 4 — Build dan Jalankan Docker

```bash
docker compose up -d --build
```

Tunggu hingga semua container selesai build. Cek statusnya:

```bash
docker ps
```

Pastikan semua container berstatus **Up**:

```
laravel_nginx  ✅
laravel_app    ✅
laravel_db     ✅
```

### 5 — Generate Application Key

```bash
docker exec laravel_app php artisan key:generate
```

### 6 — Jalankan Migration dan Seeder

```bash
docker exec laravel_app php artisan migrate --seed
```

### 7 — Set Permission Storage

```bash
docker exec laravel_app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec laravel_app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### 8 — Akses Aplikasi

Buka browser dan akses:

```
http://localhost:8000
```

---

## 🗄️ Akses Database (phpMyAdmin)

```
http://localhost:9500
```

Login menggunakan kredensial yang ada di file `.env`.

---

## 🔧 Perintah Berguna

### Menjalankan Container

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
# Jalankan migration
docker exec laravel_app php artisan migrate

# Rollback migration
docker exec laravel_app php artisan migrate:rollback

# Jalankan seeder
docker exec laravel_app php artisan db:seed

# Clear cache
docker exec laravel_app php artisan cache:clear
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan view:clear
```

### Menjalankan Test

```bash
# Jalankan semua test
docker exec laravel_app php artisan test

# Atau dari host WSL (jika PHP terinstall)
php artisan test

# Jalankan test spesifik
php artisan test tests/Feature/ProfileTest.php
```

### Melihat Log

```bash
# Log Laravel app
docker logs laravel_app --tail 50

# Log Nginx
docker logs laravel_nginx --tail 50

# Log Database
docker logs laravel_db --tail 50
```

---

## 🌿 Git Workflow

### Alur Branch

```
main          → Production (protected)
develop       → Staging/Testing
fix/branch    → Perbaikan bug
feature/branch → Fitur baru
```

### Alur Kerja Harian

```bash
# 1. Selalu mulai dari develop terbaru
git checkout develop
git pull origin develop

# 2. Buat branch baru untuk fitur/fix
git checkout -b feature/nama-fitur

# 3. Kerjakan perubahan...

# 4. Test di lokal sebelum push
php artisan test

# 5. Commit dan push
git add .
git commit -m "feat: deskripsi perubahan"
git push origin feature/nama-fitur

# 6. Buat Pull Request ke develop di GitHub
# 7. Minta review dari anggota tim
# 8. Setelah approve, merge ke develop
# 9. Develop ke main melalui PR dan approval owner
```

### Format Pesan Commit

```
feat: tambah fitur baru
fix: perbaiki bug
chore: update dependencies
docs: update dokumentasi
test: tambah/update test
refactor: refactor kode
```

---

## 🔄 CI/CD dengan Jenkins

Jenkins otomatis menjalankan pipeline setiap ada push ke GitHub.

### Stage Pipeline

```
Build → Build Frontend → Testing → Deploy Dev → Deploy Prod
```

### Akses Jenkins

```
http://localhost:8080
```

### Menjalankan Build Manual

1. Buka Jenkins di browser
2. Klik job **laravel-dev**
3. Klik **Build Now**
4. Klik **Open Blue Ocean** untuk melihat progress

---

## 📁 Struktur Project

```
WebDkost/
├── app/                    → Logic aplikasi Laravel
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
│   ├── js/                 → JavaScript/Vue
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

### Container tidak mau jalan

```bash
docker compose down
docker rmi webdkost-app -f
docker compose up -d --build
```

### Permission denied di storage

```bash
docker exec laravel_app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec laravel_app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### Database tidak konek

```bash
# Cek container DB jalan
docker ps | grep laravel_db

# Jika tidak jalan
docker compose up -d laravel_db

# Cek log DB
docker logs laravel_db --tail 30
```

### Git permission error saat checkout branch

```bash
sudo chown -R $USER:$USER storage bootstrap/cache
git config core.fileMode false
```

### Port sudah dipakai

```bash
# Cek siapa yang pakai port 8000
sudo lsof -i :8000

# Ganti port di docker-compose.yml jika perlu
```

---

## 👥 Tim

| Nama | Role | GitHub |
|---|---|---|
| DreamlandSR | Owner/Backend | @DreamlandSR |

---

## 📝 Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).
