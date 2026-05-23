# Dimsum Mak'Angga — Panduan Setup & Hosting

## 🚀 Instalasi Lokal (Development)

### Prasyarat
- PHP 8.2+
- MySQL 8+ / MariaDB 10.6+
- Composer
- Node.js 18+

### Langkah Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Copy dan edit .env
cp .env.example .env
# Edit .env: isi DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Generate app key
php artisan key:generate

# 4. Buat database MySQL baru (nama: dimsum_makangga)
# Kemudian jalankan migrasi
php artisan migrate --seed

# 5. Build assets
npm run build

# 6. Jalankan server
php artisan serve
```

### Akun Default (setelah seed)
| Role  | Email                        | Password |
|-------|------------------------------|----------|
| Admin | admin@dimsummakangga.com     | admin123 |
| User  | user@dimsummakangga.com      | user123  |

> ⚠️ Ganti password setelah pertama login!

---

## 🌐 Hosting (Shared Hosting / VPS)

### Upload Files
1. Upload semua file (kecuali `node_modules`, `vendor`, `.git`)
2. Dokumen root harus mengarah ke folder `public/`

### Konfigurasi .env untuk Production
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIE=true
```

### Perintah di Server
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Storage Link (jika ada upload file)
```bash
php artisan storage:link
```

### Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔔 Real-time Notifikasi (Reverb)

Notifikasi pesanan baru menggunakan Laravel Reverb.
Untuk production, jalankan:
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```
Atau gunakan `supervisor` untuk menjaga proses tetap berjalan.

---

## 📊 Export Excel & PDF

- Export Excel: `/admin/rekapitulasi/excel`
- Export PDF: `/admin/rekapitulasi/pdf`

Pastikan `phpoffice/phpspreadsheet` dan `barryvdh/laravel-dompdf` sudah terinstall via Composer.
