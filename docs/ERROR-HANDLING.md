# Error Handling SI-DESA

Dokumen ini membantu menangani error umum saat instalasi, demo, atau pengembangan.

## 1. Database Tidak Terkoneksi

Gejala:

```text
SQLSTATE[HY000] [2002]
Access denied for user root
Connection refused
```

Solusi:

1. Pastikan Laragon MySQL aktif.
2. Cek `.env`.

```env
DB_HOST=127.0.0.1
DB_PORT=3308
DB_DATABASE=sidesa
DB_USERNAME=root
DB_PASSWORD=
```

3. Jika MySQL memakai port default, ubah ke:

```env
DB_PORT=3306
```

4. Jalankan:

```bash
php artisan optimize:clear
```

## 2. Tabel Tidak Ditemukan

Gejala:

```text
Base table or view not found
```

Solusi:

```bash
php artisan migrate --seed
```

Jika ingin reset data demo:

```bash
php artisan migrate:fresh --seed
```

## 3. File Upload Tidak Tampil

Solusi:

```bash
php artisan storage:link
```

Pastikan file berada di:

```text
storage/app/public
```

## 4. Permission Ditolak

Gejala:

```text
403 Anda tidak memiliki izin
```

Solusi:

- Login dengan role yang sesuai.
- Cek konfigurasi permission di `config/sidesa.php`.
- Pastikan user aktif.

## 5. PDF Surat Error

Solusi:

```bash
composer install
php artisan optimize:clear
```

Pastikan package DomPDF sudah ada di `composer.json`.

## 6. QR Code Tidak Tampil

Solusi:

```bash
composer install
php artisan optimize:clear
```

Pastikan surat memiliki:

- `verification_code`
- `digital_signature`
- `letter_number`

## 7. Pagination Berantakan

Solusi sudah diterapkan di `AppServiceProvider`:

```php
Paginator::useBootstrapFive();
```

Jika masih belum berubah:

```bash
php artisan optimize:clear
```

## 8. Halaman Tidak Ditemukan

SI-DESA memiliki halaman 404 custom di:

```text
resources/views/errors/404.blade.php
```

Halaman ini muncul saat URL tidak valid atau route tidak tersedia.
