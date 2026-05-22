# SI-DESA

SI-DESA adalah aplikasi Sistem Informasi Desa berbasis Laravel untuk membantu kantor desa mengelola data kependudukan, kartu keluarga, layanan surat, bantuan sosial, pengaduan warga, dan berita desa dalam satu dashboard.

Project ini disusun dengan standar presentasi tugas akhir/skripsi dan diarahkan agar realistis digunakan sebagai prototipe operasional kantor desa.

## Tujuan Sistem

- Mempercepat pelayanan administrasi desa.
- Menata data penduduk dan kartu keluarga secara terpusat.
- Membantu pembuatan surat otomatis dengan PDF dan QR Code validasi.
- Mencatat bantuan sosial dan riwayat penyaluran.
- Menyediakan kanal pengaduan warga.
- Menampilkan dashboard statistik desa.
- Meningkatkan transparansi informasi melalui berita desa.

## Modul Utama

- Dashboard statistik desa
- Login, logout, dan hak akses role
- Data penduduk
- Kartu keluarga dan relasi anggota keluarga
- Layanan surat otomatis
- Cetak PDF surat
- QR Code validasi surat
- Berita desa
- Bantuan sosial
- Pengaduan warga
- Export dan import data penduduk

## Role Pengguna

- Super Admin: akses penuh ke seluruh sistem.
- Admin Desa: mengelola data utama dan pelayanan desa.
- Operator: membantu input dan proses administrasi harian.
- Kepala Desa: melihat laporan dan data strategis.
- Warga: akses terbatas untuk layanan warga.

## Teknologi

- Laravel
- MySQL/MariaDB
- Bootstrap
- Chart.js
- DomPDF
- QR Code
- CSV/Excel-compatible export-import

## Akun Demo

Semua akun demo memakai password:

```text
password
```

| Role | Email |
| --- | --- |
| Super Admin | superadmin@sidesa.test |
| Admin Desa | admin@sidesa.test |
| Operator | operator@sidesa.test |
| Kepala Desa | kepala@sidesa.test |
| Warga | warga@sidesa.test |

## Instalasi Cepat

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Konfigurasi database lokal yang dipakai saat pengembangan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3308
DB_DATABASE=sidesa
DB_USERNAME=root
DB_PASSWORD=
```

Panduan lengkap ada di [docs/INSTALASI.md](docs/INSTALASI.md).

## Pengujian

```bash
php artisan test
```

Skenario pengujian manual tersedia di [docs/SKENARIO-DEMO.md](docs/SKENARIO-DEMO.md).

## Dokumentasi

- [Instalasi](docs/INSTALASI.md)
- [Arsitektur](docs/ARSITEKTUR.md)
- [Panduan Pengguna](docs/PANDUAN-PENGGUNA.md)
- [Skenario Demo Sidang](docs/SKENARIO-DEMO.md)
- [Kesiapan Operasional](docs/KESIAPAN-OPERASIONAL.md)

## Catatan Pengembangan

SI-DESA dibuat bertahap agar mudah dipahami pemula. Struktur kode mengikuti pola Laravel standar: route, controller, model, migration, seeder, view, middleware, dan test.
