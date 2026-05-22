# Skenario Demo Tugas Akhir SI-DESA

Dokumen ini dipakai sebagai alur presentasi saat sidang atau demo ke kantor desa.

## 1. Pembukaan

Kalimat singkat:

> SI-DESA adalah sistem informasi desa berbasis Laravel yang membantu perangkat desa mengelola kependudukan, kartu keluarga, surat otomatis, bantuan sosial, pengaduan warga, dan berita desa dalam satu aplikasi.

Masalah yang diangkat:

- Data penduduk masih tersebar.
- Pembuatan surat manual memakan waktu.
- Riwayat bantuan sosial sulit ditelusuri.
- Pengaduan warga belum terdokumentasi.
- Kepala desa membutuhkan ringkasan data cepat.

Solusi:

- Dashboard statistik.
- Database penduduk dan KK terpusat.
- Surat otomatis dengan PDF dan QR Code.
- Pengaduan warga dengan status tindak lanjut.
- Bansos dengan riwayat penyaluran.

## 2. Demo Login dan Hak Akses

Login sebagai:

```text
admin@sidesa.test
password
```

Tunjukkan:

- Menu admin lengkap.
- Logout.
- Login sebagai warga.
- Menu warga lebih terbatas.

Poin penjelasan:

> Sistem menggunakan role agar data sensitif tidak diakses sembarang pengguna.

## 3. Demo Dashboard

Tunjukkan:

- Jumlah penduduk
- Jumlah laki-laki/perempuan
- Jumlah KK
- Statistik pekerjaan
- Statistik pendidikan
- Grafik penduduk

Poin penjelasan:

> Dashboard membantu kepala desa melihat kondisi administrasi desa secara cepat.

## 4. Demo Data Penduduk

Tunjukkan:

- Pencarian penduduk
- Filter data
- Tambah atau edit penduduk
- Validasi NIK 16 digit
- Export data

Poin penjelasan:

> Data penduduk menjadi sumber utama untuk modul surat, KK, dan bantuan sosial.

## 5. Demo Kartu Keluarga

Tunjukkan:

- Daftar KK
- Detail anggota keluarga
- Relasi penduduk berdasarkan nomor KK

Poin penjelasan:

> Sistem menjaga relasi antara kepala keluarga dan anggota keluarga.

## 6. Demo Surat Otomatis

Tunjukkan:

- Buat surat dari data penduduk
- Nomor surat otomatis
- Cetak PDF
- QR Code validasi
- Halaman verifikasi publik

Poin penjelasan:

> QR Code membantu memvalidasi keaslian surat dan mengurangi risiko pemalsuan.

## 7. Demo Bantuan Sosial

Tunjukkan:

- Data penerima bansos
- Kategori bantuan
- Catatan kelayakan
- Riwayat penyaluran

Poin penjelasan:

> Modul bansos dibuat sebagai data sensitif, sehingga pengelolaan dan aksesnya dibatasi.

## 8. Demo Pengaduan Warga

Login sebagai warga:

```text
warga@sidesa.test
password
```

Tunjukkan:

- Kirim laporan
- Nomor tiket
- Status laporan

Login sebagai admin:

```text
admin@sidesa.test
password
```

Tunjukkan:

- Admin membaca laporan
- Admin mengubah status
- Admin memberi balasan

## 9. Demo Berita Desa

Tunjukkan:

- Tambah berita
- Kategori berita
- Publikasi berita
- Tampilan publik berita

## 10. Penutup

Kalimat penutup:

> Dengan SI-DESA, proses administrasi desa menjadi lebih rapi, cepat, terdokumentasi, dan mudah dipantau oleh perangkat desa sesuai hak akses masing-masing.

## Pertanyaan Sidang yang Mungkin Muncul

### Mengapa memakai Laravel?

Laravel memiliki struktur MVC yang jelas, keamanan bawaan seperti CSRF, validasi request, migration database, Eloquent ORM, dan mudah dikembangkan.

### Bagaimana keamanan data dijaga?

Sistem memakai login, role permission, middleware akses, validasi input, proteksi upload file, dan pembatasan menu berdasarkan role.

### Apa keunggulan utama sistem?

Fitur surat otomatis dengan PDF dan QR Code, dashboard statistik, serta integrasi data penduduk dengan KK, bansos, dan pengaduan.
