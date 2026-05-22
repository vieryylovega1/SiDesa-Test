<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\FamilyCard;
use App\Models\LetterRequest;
use App\Models\NewsCategory;
use App\Models\NewsComment;
use App\Models\NewsPost;
use App\Models\Resident;
use App\Models\SocialAssistanceCategory;
use App\Models\SocialAssistanceRecipient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = collect([
            ['name' => 'Super Admin', 'email' => 'superadmin@sidesa.test', 'role' => 'super_admin'],
            ['name' => 'Admin Desa', 'email' => 'admin@sidesa.test', 'role' => 'admin_desa'],
            ['name' => 'Operator Desa', 'email' => 'operator@sidesa.test', 'role' => 'operator'],
            ['name' => 'Kepala Desa', 'email' => 'kepala@sidesa.test', 'role' => 'kepala_desa'],
            ['name' => 'Warga Contoh', 'email' => 'warga@sidesa.test', 'role' => 'warga'],
        ])->mapWithKeys(function (array $user) {
            $record = User::updateOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => bcrypt('password'), 'role' => $user['role'], 'is_active' => true]
            );

            return [$user['email'] => $record];
        });

        $residentRows = [
            ['3301011605900001', '3301010101200001', 'Kepala Keluarga', 'Siti Aminah', 'Perempuan', 'Sukamaju', '1990-05-16', 'Wiraswasta', 'SMA/SMK', 'Kawin', 'Jl. Melati', '001', '002', 'Krajan'],
            ['3301010404880004', '3301010101200001', 'Suami', 'Ahmad Fauzi', 'Laki-laki', 'Sukamaju', '1988-04-04', 'Pegawai Swasta', 'S1', 'Kawin', 'Jl. Melati', '001', '002', 'Krajan'],
            ['3301011206140005', '3301010101200001', 'Anak', 'Nabila Putri', 'Perempuan', 'Sukamaju', '2014-06-12', 'Pelajar', 'SD', 'Belum Kawin', 'Jl. Melati', '001', '002', 'Krajan'],
            ['3301012107870002', '3301010101200002', 'Kepala Keluarga', 'Budi Santoso', 'Laki-laki', 'Sukamaju', '1987-07-21', 'Petani', 'SMP', 'Kawin', 'Jl. Kenanga', '003', '001', 'Karangasem'],
            ['3301010909910006', '3301010101200002', 'Istri', 'Sri Wahyuni', 'Perempuan', 'Sukamaju', '1991-09-09', 'Ibu Rumah Tangga', 'SMA/SMK', 'Kawin', 'Jl. Kenanga', '003', '001', 'Karangasem'],
            ['3301012202160007', '3301010101200002', 'Anak', 'Raka Pratama', 'Laki-laki', 'Sukamaju', '2016-02-22', 'Pelajar', 'SD', 'Belum Kawin', 'Jl. Kenanga', '003', '001', 'Karangasem'],
            ['3301010811980003', '3301010101200003', 'Kepala Keluarga', 'Rahma Putri', 'Perempuan', 'Sentosa', '1998-11-08', 'Guru', 'S1', 'Belum Kawin', 'Jl. Mawar', '002', '003', 'Sumberrejo'],
            ['3301011505750008', '3301010101200004', 'Kepala Keluarga', 'Darto Suwito', 'Laki-laki', 'Sukamaju', '1975-05-15', 'Buruh', 'SD', 'Kawin', 'Jl. Cempaka', '004', '002', 'Krajan'],
            ['3301012709780009', '3301010101200004', 'Istri', 'Lastri', 'Perempuan', 'Sukamaju', '1978-09-27', 'Pedagang', 'SMP', 'Kawin', 'Jl. Cempaka', '004', '002', 'Krajan'],
            ['3301011001040010', '3301010101200004', 'Anak', 'Dimas Saputra', 'Laki-laki', 'Sukamaju', '2004-01-10', 'Mahasiswa', 'SMA/SMK', 'Belum Kawin', 'Jl. Cempaka', '004', '002', 'Krajan'],
            ['3301010312820011', '3301010101200005', 'Kepala Keluarga', 'Hendra Wijaya', 'Laki-laki', 'Sukamaju', '1982-12-03', 'Perangkat Desa', 'S1', 'Kawin', 'Jl. Anggrek', '002', '001', 'Karangasem'],
            ['3301012402860012', '3301010101200005', 'Istri', 'Maya Lestari', 'Perempuan', 'Sukamaju', '1986-02-24', 'Bidan', 'D1/D2/D3', 'Kawin', 'Jl. Anggrek', '002', '001', 'Karangasem'],
            ['3301011809110013', '3301010101200005', 'Anak', 'Alif Maulana', 'Laki-laki', 'Sukamaju', '2011-09-18', 'Pelajar', 'SMP', 'Belum Kawin', 'Jl. Anggrek', '002', '001', 'Karangasem'],
            ['3301010706660014', '3301010101200006', 'Kepala Keluarga', 'Slamet Riyadi', 'Laki-laki', 'Sukamaju', '1966-06-07', 'Petani', 'SD', 'Kawin', 'Jl. Padi', '005', '003', 'Sumberrejo'],
            ['3301011112700015', '3301010101200006', 'Istri', 'Tuminah', 'Perempuan', 'Sukamaju', '1970-12-11', 'Ibu Rumah Tangga', 'SD', 'Kawin', 'Jl. Padi', '005', '003', 'Sumberrejo'],
            ['3301010503950016', '3301010101200006', 'Anak', 'Eko Prasetyo', 'Laki-laki', 'Sukamaju', '1995-03-05', 'Petani', 'SMA/SMK', 'Belum Kawin', 'Jl. Padi', '005', '003', 'Sumberrejo'],
            ['3301010101800017', '3301010101200007', 'Kepala Keluarga', 'Nurhayati', 'Perempuan', 'Sukamaju', '1980-01-01', 'Penjahit', 'SMP', 'Cerai Hidup', 'Jl. Flamboyan', '001', '004', 'Tegalrejo'],
            ['3301013006060018', '3301010101200007', 'Anak', 'Citra Dewi', 'Perempuan', 'Sukamaju', '2006-06-30', 'Pelajar', 'SMA/SMK', 'Belum Kawin', 'Jl. Flamboyan', '001', '004', 'Tegalrejo'],
            ['3301011410770019', '3301010101200008', 'Kepala Keluarga', 'Joko Susilo', 'Laki-laki', 'Sukamaju', '1977-10-14', 'Pedagang', 'SMA/SMK', 'Kawin', 'Jl. Pasar', '006', '002', 'Tegalrejo'],
            ['3301012803810020', '3301010101200008', 'Istri', 'Rini Handayani', 'Perempuan', 'Sukamaju', '1981-03-28', 'Pedagang', 'SMA/SMK', 'Kawin', 'Jl. Pasar', '006', '002', 'Tegalrejo'],
            ['3301011902070021', '3301010101200008', 'Anak', 'Bayu Nugroho', 'Laki-laki', 'Sukamaju', '2007-02-19', 'Pelajar', 'SMA/SMK', 'Belum Kawin', 'Jl. Pasar', '006', '002', 'Tegalrejo'],
            ['3301012304920022', '3301010101200009', 'Kepala Keluarga', 'Agus Setiawan', 'Laki-laki', 'Sukamaju', '1992-04-23', 'Ojek Online', 'SMA/SMK', 'Kawin', 'Jl. Dahlia', '003', '004', 'Krajan'],
            ['3301011208940023', '3301010101200009', 'Istri', 'Fitriani', 'Perempuan', 'Sukamaju', '1994-08-12', 'Ibu Rumah Tangga', 'SMA/SMK', 'Kawin', 'Jl. Dahlia', '003', '004', 'Krajan'],
            ['3301010101210024', '3301010101200009', 'Anak', 'Kirana Aulia', 'Perempuan', 'Sukamaju', '2021-01-01', 'Belum Bekerja', 'Tidak Sekolah', 'Belum Kawin', 'Jl. Dahlia', '003', '004', 'Krajan'],
        ];

        $residents = collect($residentRows)->mapWithKeys(function (array $row) {
            [$nik, $kk, $relationship, $name, $gender, $birthPlace, $birthDate, $occupation, $education, $maritalStatus, $address, $rt, $rw, $hamlet] = $row;

            $resident = Resident::updateOrCreate(
                ['nik' => $nik],
                [
                    'kk' => $kk,
                    'family_relationship' => $relationship,
                    'name' => $name,
                    'gender' => $gender,
                    'birth_place' => $birthPlace,
                    'birth_date' => $birthDate,
                    'religion' => 'Islam',
                    'occupation' => $occupation,
                    'education' => $education,
                    'marital_status' => $maritalStatus,
                    'address' => $address,
                    'rt' => $rt,
                    'rw' => $rw,
                    'hamlet' => $hamlet,
                    'status' => 'Aktif',
                ]
            );

            return [$nik => $resident];
        });

        $residents->groupBy('kk')->each(function ($familyResidents, string $kk) {
            $head = $familyResidents->firstWhere('family_relationship', 'Kepala Keluarga') ?: $familyResidents->first();

            FamilyCard::updateOrCreate(
                ['number' => $kk],
                [
                    'head_name' => $head->name,
                    'address' => $head->address,
                    'rt' => $head->rt,
                    'rw' => $head->rw,
                    'hamlet' => $head->hamlet,
                ]
            );
        });

        $letterRows = [
            ['3301011605900001', 'domisili', 'Surat Keterangan Domisili', 'Keperluan administrasi perbankan.', 'Diproses', '2026-05-16'],
            ['3301012107870002', 'usaha', 'Surat Keterangan Usaha', 'Pengajuan legalitas usaha tani.', 'Verifikasi', '2026-05-15'],
            ['3301010811980003', 'tidak_mampu', 'Surat Tidak Mampu', 'Keperluan bantuan pendidikan.', 'Selesai', '2026-05-14'],
            ['3301011505750008', 'domisili', 'Surat Keterangan Domisili', 'Pendaftaran BPJS Kesehatan.', 'Selesai', '2026-05-10'],
            ['3301010312820011', 'usaha', 'Surat Keterangan Usaha', 'Pengajuan izin usaha mikro.', 'Selesai', '2026-05-09'],
            ['3301010706660014', 'tidak_mampu', 'Surat Tidak Mampu', 'Pengajuan keringanan biaya berobat.', 'Diproses', '2026-05-08'],
            ['3301010101800017', 'domisili', 'Surat Keterangan Domisili', 'Keperluan administrasi sekolah anak.', 'Verifikasi', '2026-05-07'],
            ['3301011410770019', 'usaha', 'Surat Keterangan Usaha', 'Persyaratan pengajuan KUR.', 'Selesai', '2026-05-06'],
            ['3301012304920022', 'pindah', 'Surat Keterangan Pindah', 'Rencana pindah alamat dalam kabupaten.', 'Diproses', '2026-05-05'],
        ];

        foreach ($letterRows as $index => [$nik, $code, $type, $purpose, $status, $date]) {
            $resident = $residents[$nik];
            $letterNumber = $status === 'Selesai' ? sprintf('470/%02d/V/2026', $index + 1) : null;
            $verificationCode = $status === 'Selesai' ? 'VRF-' . Str::upper(Str::random(10)) : null;

            LetterRequest::firstOrCreate(
                [
                    'resident_id' => $resident->id,
                    'letter_type' => $type,
                    'requested_at' => $date,
                ],
                [
                    'applicant_name' => $resident->name,
                    'letter_code' => $code,
                    'purpose' => $purpose,
                    'phone' => '0812345678' . str_pad((string) ($index + 10), 2, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'letter_number' => $letterNumber,
                    'verification_code' => $verificationCode,
                    'digital_signature' => $verificationCode ? hash('sha256', $letterNumber . $verificationCode) : null,
                    'signed_by' => $verificationCode ? $users['kepala@sidesa.test']->id : null,
                    'signed_at' => $verificationCode ? now()->subDays(10 - $index) : null,
                    'requested_at' => $date,
                ]
            );
        }

        $socialCategories = collect([
            ['name' => 'PKH', 'description' => 'Program Keluarga Harapan untuk keluarga rentan.'],
            ['name' => 'BPNT', 'description' => 'Bantuan pangan non tunai untuk kebutuhan pokok.'],
            ['name' => 'BLT Desa', 'description' => 'Bantuan langsung tunai dari pemerintah desa.'],
            ['name' => 'Bantuan Lansia', 'description' => 'Bantuan untuk warga lanjut usia dan rentan.'],
            ['name' => 'Bantuan Pendidikan', 'description' => 'Bantuan untuk mendukung biaya pendidikan warga.'],
        ])->mapWithKeys(function (array $category) {
            $record = SocialAssistanceCategory::firstOrCreate(
                ['slug' => Str::slug($category['name'])],
                ['name' => $category['name'], 'description' => $category['description'], 'is_active' => true]
            );

            return [$category['name'] => $record];
        });

        $recipientRows = [
            ['3301012107870002', 'BLT Desa', 'active', '2026-05-01', 'Masuk kategori keluarga rentan berdasarkan validasi perangkat RT/RW.'],
            ['3301011505750008', 'BPNT', 'active', '2026-04-20', 'Pendapatan keluarga tidak tetap dan masuk prioritas bantuan pangan.'],
            ['3301010706660014', 'Bantuan Lansia', 'active', '2026-04-18', 'Kepala keluarga lansia dengan penghasilan rendah.'],
            ['3301010101800017', 'PKH', 'active', '2026-04-15', 'Orang tua tunggal dengan anak usia sekolah.'],
            ['3301012304920022', 'Bantuan Pendidikan', 'suspended', '2026-03-22', 'Perlu verifikasi ulang dokumen pendidikan anak.'],
            ['3301012803810020', 'BPNT', 'inactive', '2026-03-10', 'Status dinonaktifkan karena pindah kategori ekonomi.'],
        ];

        foreach ($recipientRows as $index => [$nik, $categoryName, $status, $date, $note]) {
            $recipient = SocialAssistanceRecipient::firstOrCreate(
                [
                    'resident_id' => $residents[$nik]->id,
                    'social_assistance_category_id' => $socialCategories[$categoryName]->id,
                ],
                [
                    'status' => $status,
                    'registered_at' => $date,
                    'eligibility_note' => $note,
                    'created_by' => $users['admin@sidesa.test']->id,
                ]
            );

            foreach (['Maret 2026', 'April 2026', 'Mei 2026'] as $monthIndex => $period) {
                $recipient->histories()->firstOrCreate(
                    ['period' => $period, 'distributed_at' => now()->subMonths(2 - $monthIndex)->startOfMonth()->addDays(14)->format('Y-m-d')],
                    [
                        'amount' => $categoryName === 'Bantuan Pendidikan' ? 500000 : 300000,
                        'status' => $status === 'suspended' && $period === 'Mei 2026' ? 'ditunda' : 'disalurkan',
                        'description' => 'Penyaluran ' . strtolower($categoryName) . ' periode ' . $period . '.',
                        'recorded_by' => $users['operator@sidesa.test']->id,
                    ]
                );
            }
        }

        $complaintRows = [
            ['LPR-20260522-CONTOH', 'Warga Contoh', '081234567899', 'RT 001/RW 002, dekat pos ronda', 'Infrastruktur', 'Lampu jalan mati', 'Lampu jalan di dekat pos ronda mati sejak beberapa hari dan perlu diperbaiki.', 'diproses', 'Terima kasih, laporan sudah diteruskan ke petugas teknis desa.'],
            ['LPR-20260521-AB12C', 'Ahmad Fauzi', '081234567810', 'Jl. Melati RT 001/RW 002', 'Keamanan', 'Portal lingkungan rusak', 'Portal lingkungan sulit ditutup sehingga akses malam hari kurang aman.', 'baru', null],
            ['LPR-20260520-CD34E', 'Sri Wahyuni', '081234567811', 'Jl. Kenanga RT 003/RW 001', 'Kebersihan', 'Sampah menumpuk di drainase', 'Tumpukan sampah menghambat aliran air dan menimbulkan bau.', 'selesai', 'Petugas kebersihan sudah melakukan pembersihan drainase.'],
            ['LPR-20260519-EF56G', 'Darto Suwito', '081234567812', 'Jl. Cempaka RT 004/RW 002', 'Infrastruktur', 'Jalan berlubang', 'Ada lubang cukup besar di jalan utama menuju balai dusun.', 'diproses', 'Laporan sudah masuk daftar prioritas perbaikan jalan.'],
            ['LPR-20260518-GH78I', 'Maya Lestari', '081234567813', 'Jl. Anggrek RT 002/RW 001', 'Pelayanan', 'Antrian pelayanan ramai', 'Mohon tambahan informasi jam layanan agar warga tidak menumpuk.', 'selesai', 'Informasi jam layanan sudah ditempel di papan pengumuman dan website berita desa.'],
            ['LPR-20260517-IJ90K', 'Nurhayati', '081234567814', 'Jl. Flamboyan RT 001/RW 004', 'Sosial', 'Usulan pendataan bantuan', 'Ada warga lansia yang perlu didata untuk bantuan sosial.', 'diproses', 'Petugas akan melakukan verifikasi lapangan bersama RT/RW.'],
        ];

        foreach ($complaintRows as [$ticket, $reporter, $phone, $address, $category, $title, $description, $status, $reply]) {
            Complaint::firstOrCreate(
                ['ticket_number' => $ticket],
                [
                    'user_id' => $users['warga@sidesa.test']->id,
                    'reporter_name' => $reporter,
                    'phone' => $phone,
                    'address' => $address,
                    'category' => $category,
                    'title' => $title,
                    'description' => $description,
                    'status' => $status,
                    'admin_reply' => $reply,
                    'replied_by' => $reply ? $users['admin@sidesa.test']->id : null,
                    'replied_at' => $reply ? now()->subDays(2) : null,
                ]
            );
        }

        $categories = collect(['Pemerintahan', 'Pembangunan', 'Kesehatan', 'Kegiatan', 'Pertanian', 'UMKM'])->mapWithKeys(function (string $name) {
            $category = NewsCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );

            return [$name => $category];
        });

        $postRows = [
            ['Pemerintahan', 'Musyawarah Desa Sukamaju 2026', 'Pemerintah Desa Sukamaju menggelar musyawarah desa untuk menyusun prioritas pembangunan dan pelayanan publik.', 'published', 8],
            ['Pembangunan', 'Pengecoran Jalan Usaha Tani Dimulai', 'Pembangunan jalan usaha tani dimulai untuk memperlancar akses petani menuju area persawahan.', 'published', 7],
            ['Kesehatan', 'Posyandu Balita dan Lansia Bulan Mei', 'Kegiatan posyandu rutin dilaksanakan dengan pemeriksaan kesehatan balita, ibu hamil, dan lansia.', 'published', 5],
            ['Kegiatan', 'Kerja Bakti Bersama Warga Dusun Krajan', 'Warga Dusun Krajan melaksanakan kerja bakti membersihkan saluran air dan lingkungan sekitar.', 'published', 4],
            ['Pertanian', 'Pelatihan Pembuatan Pupuk Organik', 'Kelompok tani mengikuti pelatihan pembuatan pupuk organik untuk mendukung pertanian berkelanjutan.', 'draft', 3],
            ['UMKM', 'Pendataan UMKM Desa Sukamaju', 'Pemerintah desa membuka pendataan pelaku UMKM untuk program pembinaan dan pemasaran produk lokal.', 'published', 2],
        ];

        foreach ($postRows as [$categoryName, $title, $excerpt, $status, $daysAgo]) {
            $post = NewsPost::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'news_category_id' => $categories[$categoryName]->id,
                    'user_id' => $users['admin@sidesa.test']->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $excerpt . "\n\nKegiatan ini menjadi bagian dari komitmen Pemerintah Desa Sukamaju untuk meningkatkan pelayanan, transparansi informasi, dan partisipasi masyarakat dalam pembangunan desa.",
                    'status' => $status,
                    'published_at' => $status === 'published' ? now()->subDays($daysAgo) : null,
                ]
            );

            if ($status === 'published') {
                foreach ([
                    ['Warga Sukamaju', 'Informasi ini sangat bermanfaat untuk warga.', 'approved'],
                    ['Ketua RT', 'Siap membantu menyampaikan informasi ke warga.', 'pending'],
                ] as [$name, $comment, $commentStatus]) {
                    NewsComment::firstOrCreate(
                        ['news_post_id' => $post->id, 'name' => $name, 'comment' => $comment],
                        ['email' => Str::slug($name) . '@example.com', 'status' => $commentStatus]
                    );
                }
            }
        }
    }
}
