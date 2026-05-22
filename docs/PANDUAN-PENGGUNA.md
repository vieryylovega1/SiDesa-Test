# Panduan Pengguna SI-DESA

Panduan ini menjelaskan cara menggunakan fitur utama SI-DESA untuk demo tugas akhir dan penggunaan kantor desa.

## 1. Login

1. Buka aplikasi.
2. Masukkan email dan password.
3. Setelah berhasil, pengguna diarahkan ke dashboard.

Setiap role memiliki menu berbeda sesuai hak akses.

## 2. Dashboard

Dashboard menampilkan ringkasan:

- Jumlah penduduk
- Jumlah laki-laki dan perempuan
- Jumlah kartu keluarga
- Jumlah RT/RW
- Grafik penduduk
- Statistik pekerjaan dan pendidikan

Dashboard digunakan kepala desa dan admin untuk melihat kondisi desa secara cepat.

## 3. Data Penduduk

Menu: **Penduduk**

Fitur:

- Tambah penduduk
- Edit penduduk
- Detail penduduk
- Hapus penduduk
- Pencarian cepat
- Filter gender, pendidikan, status, RT, RW
- Import data
- Export data

Aturan penting:

- NIK wajib 16 digit.
- Nomor KK wajib 16 digit.
- Foto penduduk bersifat opsional.

## 4. Kartu Keluarga

Menu: **Kartu Keluarga**

Fitur:

- Tambah KK
- Edit KK
- Detail anggota keluarga
- Sinkron data KK dari penduduk

Relasi sistem:

```text
1 Kartu Keluarga memiliki banyak Penduduk
```

## 5. Layanan Surat

Menu: **Layanan Surat**

Fitur:

- Buat permohonan surat
- Pilih jenis surat
- Ambil data otomatis dari penduduk
- Generate nomor surat otomatis
- Cetak PDF
- QR Code validasi
- Verifikasi surat publik

Jenis surat contoh:

- Surat domisili
- Surat usaha
- Surat kematian
- Surat pindah
- Surat tidak mampu

## 6. Berita Desa

Menu: **Berita Desa**

Fitur:

- Tambah berita
- Edit berita
- Upload gambar
- Kategori berita
- Publikasi berita
- Komentar warga

## 7. Bantuan Sosial

Menu: **Bantuan Sosial**

Fitur:

- Data penerima bantuan
- Kategori bantuan
- Status aktif/nonaktif/ditangguhkan
- Riwayat penyaluran
- Catatan kelayakan penerima

Data bansos termasuk data sensitif, sehingga aksesnya dibatasi oleh role.

## 8. Pengaduan Warga

Menu: **Pengaduan**

Fitur:

- Warga mengirim laporan
- Upload foto pendukung
- Nomor tiket otomatis
- Admin memberi balasan
- Status laporan: baru, diproses, selesai, ditolak

Warga hanya dapat melihat laporan miliknya sendiri.

## 9. Hak Akses

Ringkasan:

- Super Admin: seluruh fitur.
- Admin Desa: pengelolaan utama.
- Operator: input dan proses data.
- Kepala Desa: melihat data dan laporan.
- Warga: layanan warga terbatas.

## 10. Tips Demo

Gunakan urutan demo:

1. Login sebagai Admin Desa.
2. Tampilkan dashboard.
3. Buka data penduduk.
4. Buat surat dari data penduduk.
5. Cetak PDF dan tunjukkan QR Code.
6. Buka bantuan sosial.
7. Buka pengaduan warga.
8. Login sebagai Warga dan tunjukkan akses terbatas.
