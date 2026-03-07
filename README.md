# WebDkost

Aplikasi manajemen kost berbasis web menggunakan Laravel.

## Requirements
- Docker
- Docker Compose

## Cara Menjalankan

1. Clone repository
```bash
git clone https://github.com/DreamlandSR/WebDkost.git
cd WebDkost
```

2. Copy env
```bash
cp .env.example .env
```

3. Jalankan Docker
```bash
docker-compose up -d --build
```

4. Install dependencies
```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

5. Build assets
```bash
docker-compose exec app bash
npm install && npm run build
```

6. Buka browser
```
http://localhost:8000
```
