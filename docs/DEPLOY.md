# Deploy Guide SI-DESA

Panduan ini menjelaskan langkah umum deploy SI-DESA ke hosting/VPS.

## 1. Persiapan Server

Kebutuhan:

- PHP sesuai versi di `composer.json`
- Composer
- MySQL/MariaDB
- Web server Apache/Nginx
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `zip`

## 2. Upload Project

Upload semua file project ke server, kecuali:

```text
vendor/
node_modules/
.env
```

## 3. Install Dependency

```bash
composer install --no-dev --optimize-autoloader
```

Jika memakai asset frontend:

```bash
npm install
npm run build
```

## 4. Konfigurasi `.env` Production

```env
APP_NAME=SiDesa
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-desa.go.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database
```

Lalu jalankan:

```bash
php artisan key:generate
```

## 5. Migrasi Database

Untuk server baru:

```bash
php artisan migrate --seed --force
```

Untuk server yang sudah punya data:

```bash
php artisan migrate --force
```

## 6. Storage Link

```bash
php artisan storage:link
```

## 7. Optimasi Production

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 8. Arahkan Web Root

Web root harus diarahkan ke folder:

```text
public/
```

Jangan arahkan domain ke root project Laravel.

## 9. Permission Folder

Pastikan folder berikut bisa ditulis oleh web server:

```text
storage/
bootstrap/cache/
```

## 10. Checklist Setelah Deploy

- Login berhasil.
- Dashboard tampil.
- Upload file berjalan.
- PDF surat bisa dicetak.
- QR Code validasi bisa dibuka.
- Backup database berhasil.
- `APP_DEBUG=false`.
- Password akun demo sudah diganti.

## 11. Update Aplikasi

Sebelum update:

```bash
php artisan sidesa:backup-database
```

Setelah upload kode baru:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
