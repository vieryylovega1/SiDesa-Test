# Testing Dasar SI-DESA

Dokumen ini berisi cara menguji fitur utama SI-DESA sebelum demo atau presentasi.

## 1. Jalankan Test Otomatis

```bash
php artisan test
```

Target berhasil:

```text
PASS
```

Test otomatis mencakup:

- Halaman login.
- Dashboard setelah login.
- Statistik dashboard.
- Filter dan export penduduk.
- Detail kartu keluarga.
- Generate PDF surat dan verifikasi publik.
- Berita dan komentar.
- Bantuan sosial.
- Pengaduan warga.

## 2. Test Manual Login

Gunakan akun:

```text
admin@sidesa.test
password
```

Cek:

- Login berhasil.
- Dashboard tampil.
- Sidebar tampil sesuai role.
- Logout berhasil.

## 3. Test Data Penduduk

Cek menu **Penduduk**:

- Pencarian nama.
- Filter gender, pendidikan, status, RT, RW.
- Pagination.
- Detail penduduk.
- Export PDF.
- Export Excel/CSV.

Validasi:

- NIK harus 16 digit.
- Nomor KK harus 16 digit.

## 4. Test Surat

Cek menu **Layanan Surat**:

- Buat surat dari data penduduk.
- Nomor surat otomatis.
- Cetak PDF.
- QR Code tampil.
- URL verifikasi surat dapat dibuka.
- Pagination halaman 1 dan halaman 2 normal.

## 5. Test Bantuan Sosial

Cek menu **Bantuan Sosial**:

- Tambah penerima.
- Cegah duplikasi penerima pada kategori sama.
- Tambah riwayat penyaluran.
- Filter status dan kategori.

## 6. Test Pengaduan

Cek sebagai warga:

- Kirim laporan.
- Lihat laporan sendiri.
- Tidak bisa melihat laporan warga lain.

Cek sebagai admin:

- Lihat semua laporan.
- Ubah status.
- Beri balasan admin.

## 7. Test UI

Cek:

- Sidebar collapsible.
- Dark mode.
- Breadcrumb.
- Global search.
- Toast notification.
- SweetAlert saat hapus data.
- Tampilan mobile.

## 8. Test 404

Buka URL tidak valid:

```text
http://127.0.0.1:8000/halaman-tidak-ada
```

Target:

- Muncul halaman 404 custom SI-DESA.
- Tombol kembali tersedia.
