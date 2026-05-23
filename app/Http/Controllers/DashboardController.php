<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\FamilyCard;
use App\Models\LetterRequest;
use App\Models\NewsPost;
use App\Models\Resident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $letters = LetterRequest::latest()->take(5)->get();
        $statistics = $this->statistics();

        return view('dashboard', [
            'stats' => [
                ['label' => 'Total Penduduk', 'value' => number_format($statistics['summary']['residents'], 0, ',', '.'), 'trend' => 'Data aktif tersimpan', 'icon' => 'bi-people'],
                ['label' => 'Laki-laki / Perempuan', 'value' => $statistics['summary']['male'] . ' / ' . $statistics['summary']['female'], 'trend' => 'Komposisi penduduk', 'icon' => 'bi-gender-ambiguous'],
                ['label' => 'Kartu Keluarga', 'value' => number_format($statistics['summary']['families'], 0, ',', '.'), 'trend' => 'Berdasarkan nomor KK', 'icon' => 'bi-house-heart'],
                ['label' => 'RT / RW', 'value' => $statistics['summary']['rt'] . ' / ' . $statistics['summary']['rw'], 'trend' => $statistics['summary']['neighborhoods'] . ' kombinasi RT-RW', 'icon' => 'bi-signpost-split'],
            ],
            'statistics' => $statistics,
            'letters' => $letters,
            'agendas' => collect($this->agendaData())->take(4),
        ]);
    }

    public function agenda()
    {
        $agendas = collect($this->agendaData());

        return view('agenda.index', [
            'agendas' => $agendas,
            'goodAgendas' => $agendas->where('kategori', 'baik')->values(),
            'badAgendas' => $agendas->where('kategori', 'perhatian')->values(),
            'summary' => [
                'total' => $agendas->count(),
                'baik' => $agendas->where('kategori', 'baik')->count(),
                'perhatian' => $agendas->where('kategori', 'perhatian')->count(),
                'selesai' => $agendas->where('status', 'Selesai')->count(),
            ],
        ]);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->statistics());
    }

    public function citizenPortal()
    {
        return view('portal-warga.index', [
            'letters' => LetterRequest::where('applicant_name', auth()->user()->name)->latest()->take(5)->get(),
        ]);
    }

    public function search(Request $request)
    {
        $query = trim($request->string('q')->toString());

        return view('search.index', [
            'query' => $query,
            'residents' => $query === '' ? collect() : Resident::query()
                ->where('name', 'like', "%{$query}%")
                ->orWhere('nik', 'like', "%{$query}%")
                ->orWhere('kk', 'like', "%{$query}%")
                ->limit(8)
                ->get(),
            'familyCards' => $query === '' ? collect() : FamilyCard::query()
                ->where('number', 'like', "%{$query}%")
                ->orWhere('head_name', 'like', "%{$query}%")
                ->limit(8)
                ->get(),
            'letters' => $query === '' ? collect() : LetterRequest::query()
                ->where('applicant_name', 'like', "%{$query}%")
                ->orWhere('letter_type', 'like', "%{$query}%")
                ->orWhere('letter_number', 'like', "%{$query}%")
                ->limit(8)
                ->get(),
            'complaints' => $query === '' ? collect() : Complaint::query()
                ->where('ticket_number', 'like', "%{$query}%")
                ->orWhere('reporter_name', 'like', "%{$query}%")
                ->orWhere('title', 'like', "%{$query}%")
                ->limit(8)
                ->get(),
            'posts' => $query === '' ? collect() : NewsPost::query()
                ->where('title', 'like', "%{$query}%")
                ->orWhere('excerpt', 'like', "%{$query}%")
                ->limit(8)
                ->get(),
        ]);
    }

    private function statistics(): array
    {
        $residents = Resident::query()->get();

        return [
            'updated_at' => now()->translatedFormat('d M Y H:i:s'),
            'summary' => [
                'residents' => $residents->count(),
                'male' => $residents->where('gender', 'Laki-laki')->count(),
                'female' => $residents->where('gender', 'Perempuan')->count(),
                'families' => $residents->pluck('kk')->filter()->unique()->count(),
                'rt' => $residents->pluck('rt')->filter()->unique()->count(),
                'rw' => $residents->pluck('rw')->filter()->unique()->count(),
                'neighborhoods' => $residents->map(fn (Resident $resident) => $resident->rt . '/' . $resident->rw)->filter()->unique()->count(),
            ],
            'gender' => $this->groupCount($residents, 'gender'),
            'occupation' => $this->groupCount($residents, 'occupation', 'Belum Diisi'),
            'education' => $this->groupCount($residents, 'education', 'Tidak Diisi'),
            'monthly' => $this->monthlyResidentTrend($residents),
        ];
    }

    private function groupCount(Collection $residents, string $field, string $fallback = 'Tidak Diisi'): array
    {
        return $residents
            ->groupBy(fn (Resident $resident) => $resident->{$field} ?: $fallback)
            ->map(fn (Collection $items, string $label) => ['label' => $label, 'total' => $items->count()])
            ->sortByDesc('total')
            ->values()
            ->all();
    }

    private function monthlyResidentTrend(Collection $residents): array
    {
        $months = collect(range(5, 0))->map(function (int $minus) {
            $date = now()->subMonths($minus);

            return [
                'key' => $date->format('Y-m'),
                'label' => $date->translatedFormat('M Y'),
                'total' => 0,
            ];
        });

        $grouped = $residents->groupBy(fn (Resident $resident) => Carbon::parse($resident->created_at)->format('Y-m'));

        return $months
            ->map(function (array $month) use ($grouped) {
                $month['total'] = $grouped->get($month['key'], collect())->count();

                return $month;
            })
            ->values()
            ->all();
    }

    private function agendaData(): array
    {
        return [
            [
                'tanggal' => '24 Mei',
                'waktu' => '08.00 - 11.00 WIB',
                'judul' => 'Posyandu Balita dan Lansia Terpadu',
                'kategori' => 'baik',
                'label' => 'Kabar Baik',
                'lokasi' => 'Gedung PKK Desa',
                'status' => 'Terjadwal',
                'penanggung_jawab' => 'Kader Posyandu Melati',
                'ringkasan' => 'Cakupan pemeriksaan kesehatan meningkat karena jadwal posyandu digabung dengan layanan lansia.',
                'dampak' => 'Warga lebih mudah mendapatkan layanan timbang balita, cek tekanan darah, dan edukasi gizi.',
                'tindak_lanjut' => 'Siapkan daftar hadir, vitamin, alat ukur, dan laporan rekap pelayanan.',
            ],
            [
                'tanggal' => '26 Mei',
                'waktu' => '19.30 - 21.30 WIB',
                'judul' => 'Musyawarah Rencana Perbaikan Drainase RT 003',
                'kategori' => 'perhatian',
                'label' => 'Perlu Perhatian',
                'lokasi' => 'Balai Dusun Krajan',
                'status' => 'Prioritas',
                'penanggung_jawab' => 'Ketua RT 003/RW 001',
                'ringkasan' => 'Drainase tersumbat menyebabkan genangan saat hujan deras dan perlu keputusan penanganan cepat.',
                'dampak' => 'Risiko air masuk ke halaman warga dan mengganggu akses jalan lingkungan.',
                'tindak_lanjut' => 'Verifikasi titik sumbatan, hitung kebutuhan material, dan buat jadwal kerja bakti.',
            ],
            [
                'tanggal' => '28 Mei',
                'waktu' => '08.00 - 15.00 WIB',
                'judul' => 'Pelatihan UMKM Produk Olahan Pangan',
                'kategori' => 'baik',
                'label' => 'Kabar Baik',
                'lokasi' => 'Aula Kantor Desa',
                'status' => 'Terjadwal',
                'penanggung_jawab' => 'Kasi Kesejahteraan',
                'ringkasan' => 'Pelaku usaha kecil mendapat pelatihan kemasan, perizinan sederhana, dan pemasaran digital.',
                'dampak' => 'Produk warga lebih siap dipasarkan dan berpeluang menambah pendapatan keluarga.',
                'tindak_lanjut' => 'Kumpulkan data peserta, dokumentasi kegiatan, dan daftar usaha untuk pendampingan lanjutan.',
            ],
            [
                'tanggal' => '30 Mei',
                'waktu' => '09.00 - 12.00 WIB',
                'judul' => 'Verifikasi Ulang Penerima Bantuan Sosial',
                'kategori' => 'perhatian',
                'label' => 'Perlu Perhatian',
                'lokasi' => 'Ruang Pelayanan Desa',
                'status' => 'Butuh Validasi',
                'penanggung_jawab' => 'Admin Desa dan Operator Bansos',
                'ringkasan' => 'Ada beberapa data penerima yang perlu dicocokkan ulang dengan kondisi terbaru keluarga.',
                'dampak' => 'Penyaluran bantuan bisa tertunda bila data belum valid dan berpotensi tidak tepat sasaran.',
                'tindak_lanjut' => 'Cek NIK, KK, status ekonomi, dan rekomendasi RT/RW sebelum daftar penerima dikunci.',
            ],
            [
                'tanggal' => '02 Jun',
                'waktu' => '07.00 - 10.00 WIB',
                'judul' => 'Gotong Royong Pembersihan Jalan Usaha Tani',
                'kategori' => 'baik',
                'label' => 'Kabar Baik',
                'lokasi' => 'Area Persawahan RW 002',
                'status' => 'Terjadwal',
                'penanggung_jawab' => 'Kelompok Tani Makmur',
                'ringkasan' => 'Warga dan kelompok tani membersihkan akses angkut hasil panen sebelum musim panen raya.',
                'dampak' => 'Mobilitas petani membaik dan biaya angkut hasil pertanian dapat ditekan.',
                'tindak_lanjut' => 'Koordinasikan alat kerja, konsumsi, dokumentasi, dan daftar kebutuhan perbaikan lanjutan.',
            ],
            [
                'tanggal' => '04 Jun',
                'waktu' => '20.00 - 21.00 WIB',
                'judul' => 'Rapat Penanganan Lampu Jalan Mati',
                'kategori' => 'perhatian',
                'label' => 'Perlu Perhatian',
                'lokasi' => 'Pos Ronda RT 006',
                'status' => 'Menunggu Tindak Lanjut',
                'penanggung_jawab' => 'Kaur Umum',
                'ringkasan' => 'Beberapa titik lampu jalan mati sehingga warga melaporkan area terasa kurang aman pada malam hari.',
                'dampak' => 'Aktivitas warga malam hari terganggu dan risiko kecelakaan kecil meningkat.',
                'tindak_lanjut' => 'Inventaris titik lampu rusak, cek anggaran perbaikan, lalu jadwalkan teknisi.',
            ],
        ];
    }
}
