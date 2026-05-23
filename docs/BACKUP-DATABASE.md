# Backup Database SI-DESA

Backup database penting sebelum demo, deploy, atau perubahan besar pada data.

## 1. Backup Otomatis Lewat Artisan

Jalankan:

```bash
php artisan sidesa:backup-database
```

File backup akan dibuat di:

```text
storage/app/backups
```

Contoh nama file:

```text
sidesa_20260523_101530.sql
```

## 2. Backup Ke Folder Khusus

```bash
php artisan sidesa:backup-database --path=C:\backup-sidesa
```

## 3. Syarat Backup

Command ini membutuhkan `mysqldump`.

Pada Laragon, `mysqldump` biasanya tersedia di folder MySQL Laragon. Jika command gagal, tambahkan folder MySQL Laragon ke PATH Windows.

Contoh lokasi:

```text
C:\laragon\bin\mysql\mysql-8.x\bin
```

## 4. Backup Manual Lewat phpMyAdmin

1. Buka phpMyAdmin.
2. Pilih database `sidesa`.
3. Klik **Export**.
4. Pilih format SQL.
5. Klik **Go**.

## 5. Restore Database

Cara restore lewat terminal:

```bash
mysql -u root -P 3308 sidesa < storage/app/backups/nama_file_backup.sql
```

Jika memakai port default:

```bash
mysql -u root -P 3306 sidesa < storage/app/backups/nama_file_backup.sql
```

## 6. SOP Backup Untuk Kantor Desa

- Backup minimal 1 kali seminggu.
- Backup sebelum update aplikasi.
- Simpan backup di komputer berbeda atau cloud storage.
- Jangan bagikan file backup karena berisi data sensitif warga.
- Gunakan nama folder berdasarkan tanggal.
