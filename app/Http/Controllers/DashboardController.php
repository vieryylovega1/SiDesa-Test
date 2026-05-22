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
            'agendas' => [
                ['tanggal' => '18 Mei', 'judul' => 'Musyawarah Dusun', 'lokasi' => 'Balai Desa'],
                ['tanggal' => '21 Mei', 'judul' => 'Posyandu Balita', 'lokasi' => 'Gedung PKK'],
                ['tanggal' => '24 Mei', 'judul' => 'Gotong Royong Irigasi', 'lokasi' => 'Dusun Krajan'],
            ],
        ]);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->statistics());
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
}
