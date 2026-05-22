# Panduan Instalasi SI-DESA

Panduan ini ditujukan untuk menjalankan SI-DESA di komputer lokal menggunakan Laragon.

## 1. Kebutuhan Sistem

- PHP 8.2 atau lebih baru
- Composer
- MySQL/MariaDB dari Laragon
- Ekstensi PHP umum Laravel: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`

## 2. Buat Database

Buka Laragon, aktifkan Apache/Nginx dan MySQL. Buat database:

```sql
CREATE DATABASE sidesa;
```

Jika ingin mengikuti nama tugas akhir `si_desa`, buat:

```sql
CREATE DATABASE si_desa;
```

Lalu sesuaikan `DB_DATABASE` di `.env`.

## 3. Konfigurasi `.env`

Contoh untuk Laragon MySQL port `3308`:

```env
APP_NAME=SiDesa
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3308
DB_DATABASE=sidesa
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL memakai port default, ubah `DB_PORT=3306`.

## 4. Install Dependency

```bash
composer install
php artisan key:generate
php artisan storage:link
```

## 5. Migrasi dan Seeder

```bash
php artisan migrate --seed
```

Seeder akan membuat akun demo dan data contoh untuk penduduk, KK, surat, berita, bansos, dan pengaduan.

## 6. Jalankan Aplikasi

```bash
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

## 7. Akun Login

Semua akun memakai password `password`.

| Role | Email |
| --- | --- |
| Super Admin | superadmin@sidesa.test |
| Admin Desa | admin@sidesa.test |
| Operator | operator@sidesa.test |
| Kepala Desa | kepala@sidesa.test |
| Warga | warga@sidesa.test |

## Error Umum

### Access denied for user root

Periksa bagian ini di `.env`:

```env
DB_PORT=3308
DB_USERNAME=root
DB_PASSWORD=
```

Jika Laragon memakai port `3306`, ganti `DB_PORT=3306`.

### Tabel tidak ditemukan

Jalankan:

```bash
php artisan migrate --seed
php artisan optimize:clear
```

### Foto atau file upload tidak tampil

Jalankan:

```bash
php artisan storage:link
```
