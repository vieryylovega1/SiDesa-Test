# Arsitektur SI-DESA

SI-DESA memakai arsitektur Laravel MVC dengan pembagian modul berdasarkan area kerja kantor desa.

## 1. Pembagian Modul

```text
Dashboard
Master Data
Kependudukan
Surat Menyurat
Bantuan Sosial
Pengaduan
Berita Desa
Dokumen
Laporan
Pengaturan
```

Pada implementasi saat ini, modul yang sudah aktif adalah:

- Dashboard
- Kependudukan
- Kartu Keluarga
- Surat Menyurat
- Bantuan Sosial
- Pengaduan
- Berita Desa
- User dan Hak Akses

## 2. Struktur Folder Utama

```text
app/
  Http/Controllers/
  Models/
config/
database/
  migrations/
  seeders/
resources/
  views/
routes/
  web.php
tests/
  Feature/
```

## 3. Alur Request

```text
Browser
  -> Route web.php
  -> Middleware auth/permission
  -> Controller
  -> Model Eloquent
  -> Database
  -> Blade View
  -> Browser
```

## 4. Relasi Data Penting

### Kartu Keluarga dan Penduduk

```text
FamilyCard
  hasMany Resident berdasarkan nomor KK

Resident
  belongsTo FamilyCard berdasarkan nomor KK
```

### Surat dan Penduduk

```text
Resident
  hasMany LetterRequest

LetterRequest
  belongsTo Resident
```

### Bantuan Sosial

```text
SocialAssistanceCategory
  hasMany SocialAssistanceRecipient

SocialAssistanceRecipient
  belongsTo Resident
  belongsTo SocialAssistanceCategory
  hasMany SocialAssistanceHistory
```

### Pengaduan Warga

```text
User
  hasMany Complaint

Complaint
  belongsTo User sebagai pelapor
  belongsTo User sebagai petugas pembalas
```

## 5. Keamanan

SI-DESA menerapkan:

- Login wajib untuk dashboard.
- Role pengguna.
- Permission per menu.
- Middleware akses.
- Validasi input.
- Proteksi CSRF bawaan Laravel.
- Validasi upload gambar.
- Pembatasan akses data sensitif.

## 6. Alasan Pemilihan Laravel

Laravel dipilih karena:

- Struktur MVC mudah dipahami.
- Migration memudahkan desain database.
- Eloquent memudahkan relasi data.
- Blade cocok untuk dashboard admin.
- Middleware dan validasi mendukung keamanan aplikasi.
- Ekosistem package mendukung PDF, QR Code, dan export data.

## 7. Standar Pengembangan Berikutnya

Untuk modul baru, gunakan pola:

```text
Migration
Model
Controller
Route
Blade View
Seeder
Feature Test
```

Jika prosesnya kompleks, tambahkan:

```text
Form Request
Service Class
Policy
Observer
```

Contoh proses kompleks:

- Generate nomor surat otomatis.
- Import data penduduk.
- Audit log perubahan data.
- Backup database.
- Export laporan.
