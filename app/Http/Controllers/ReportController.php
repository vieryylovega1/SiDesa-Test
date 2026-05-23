<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\SocialAssistanceHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private const TYPES = [
        'penduduk-rt-rw' => 'Penduduk per RT/RW',
        'kelahiran' => 'Laporan Kelahiran',
        'kematian' => 'Laporan Kematian',
        'pindah-datang' => 'Laporan Pindah Datang',
        'bantuan-sosial' => 'Laporan Bantuan Sosial',
    ];

    public function index(Request $request)
    {
        $type = $this->validType($request->string('type')->toString());
        $filters = $this->filters($request);
        $report = $this->buildReport($type, $filters);

        return view('reports.index', [
            'types' => self::TYPES,
            'type' => $type,
            'filters' => $filters,
            'report' => $report,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $type = $this->validType($request->string('type')->toString());
        $filters = $this->filters($request);
        $report = $this->buildReport($type, $filters);

        return Pdf::loadView('reports.pdf', [
            'types' => self::TYPES,
            'type' => $type,
            'filters' => $filters,
            'report' => $report,
        ])->setPaper('a4', 'landscape')
            ->stream($type . '-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $type = $this->validType($request->string('type')->toString());
        $filters = $this->filters($request);
        $report = $this->buildReport($type, $filters);
        $filename = $type . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($report) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $report['headers']);

            foreach ($report['rows'] as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function validType(string $type): string
    {
        return array_key_exists($type, self::TYPES) ? $type : 'penduduk-rt-rw';
    }

    private function filters(Request $request): array
    {
        return [
            'start_date' => $request->date('start_date')?->format('Y-m-d') ?: now()->startOfYear()->format('Y-m-d'),
            'end_date' => $request->date('end_date')?->format('Y-m-d') ?: now()->format('Y-m-d'),
            'rt' => $request->string('rt')->toString(),
            'rw' => $request->string('rw')->toString(),
        ];
    }

    private function buildReport(string $type, array $filters): array
    {
        return match ($type) {
            'kelahiran' => $this->birthReport($filters),
            'kematian' => $this->statusReport($filters, 'Meninggal', 'Laporan penduduk berstatus meninggal berdasarkan tanggal pencatatan.'),
            'pindah-datang' => $this->movingReport($filters),
            'bantuan-sosial' => $this->socialAssistanceReport($filters),
            default => $this->residentByNeighborhoodReport($filters),
        };
    }

    private function residentBaseQuery(array $filters)
    {
        return Resident::query()
            ->when($filters['rt'], fn ($query) => $query->where('rt', $filters['rt']))
            ->when($filters['rw'], fn ($query) => $query->where('rw', $filters['rw']));
    }

    private function residentByNeighborhoodReport(array $filters): array
    {
        $residents = $this->residentBaseQuery($filters)->get();

        $rows = $residents
            ->groupBy(fn (Resident $resident) => $resident->rt . '/' . $resident->rw)
            ->map(function (EloquentCollection $items) {
                return [
                    'RT ' . $items->first()->rt . '/RW ' . $items->first()->rw,
                    $items->first()->hamlet ?: '-',
                    $items->count(),
                    $items->where('gender', 'Laki-laki')->count(),
                    $items->where('gender', 'Perempuan')->count(),
                    $items->pluck('kk')->unique()->count(),
                ];
            })
            ->sortBy(fn (array $row) => $row[0])
            ->values()
            ->all();

        return [
            'title' => 'Laporan Penduduk per RT/RW',
            'description' => 'Rekap jumlah penduduk, komposisi gender, dan KK per wilayah.',
            'headers' => ['Wilayah', 'Dusun', 'Penduduk', 'Laki-laki', 'Perempuan', 'KK'],
            'rows' => $rows,
            'summary' => [
                'Total Penduduk' => $residents->count(),
                'Total KK' => $residents->pluck('kk')->unique()->count(),
                'Total RT/RW' => count($rows),
            ],
        ];
    }

    private function birthReport(array $filters): array
    {
        $residents = $this->residentBaseQuery($filters)
            ->whereBetween('birth_date', [$filters['start_date'], $filters['end_date']])
            ->orderBy('birth_date')
            ->get();

        return [
            'title' => 'Laporan Kelahiran',
            'description' => 'Daftar penduduk berdasarkan rentang tanggal lahir.',
            'headers' => ['NIK', 'Nama', 'Jenis Kelamin', 'Tanggal Lahir', 'Orang Tua/KK', 'Alamat'],
            'rows' => $residents->map(fn (Resident $resident) => [
                $resident->nik,
                $resident->name,
                $resident->gender,
                $resident->birth_date?->format('d-m-Y'),
                $resident->kk,
                $resident->address . ' RT ' . $resident->rt . '/RW ' . $resident->rw,
            ])->all(),
            'summary' => [
                'Total Kelahiran' => $residents->count(),
                'Laki-laki' => $residents->where('gender', 'Laki-laki')->count(),
                'Perempuan' => $residents->where('gender', 'Perempuan')->count(),
            ],
        ];
    }

    private function statusReport(array $filters, string $status, string $description): array
    {
        $residents = $this->residentBaseQuery($filters)
            ->where('status', $status)
            ->whereBetween('updated_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
            ])
            ->latest('updated_at')
            ->get();

        return [
            'title' => 'Laporan ' . $status,
            'description' => $description,
            'headers' => ['NIK', 'Nama', 'Jenis Kelamin', 'Tanggal Catat', 'KK', 'Alamat'],
            'rows' => $residents->map(fn (Resident $resident) => [
                $resident->nik,
                $resident->name,
                $resident->gender,
                $resident->updated_at?->format('d-m-Y'),
                $resident->kk,
                $resident->address . ' RT ' . $resident->rt . '/RW ' . $resident->rw,
            ])->all(),
            'summary' => [
                'Total Data' => $residents->count(),
                'Laki-laki' => $residents->where('gender', 'Laki-laki')->count(),
                'Perempuan' => $residents->where('gender', 'Perempuan')->count(),
            ],
        ];
    }

    private function movingReport(array $filters): array
    {
        $residents = $this->residentBaseQuery($filters)
            ->whereIn('status', ['Pindah', 'Aktif'])
            ->whereBetween('created_at', [
                Carbon::parse($filters['start_date'])->startOfDay(),
                Carbon::parse($filters['end_date'])->endOfDay(),
            ])
            ->latest()
            ->get();

        return [
            'title' => 'Laporan Pindah Datang',
            'description' => 'Penduduk datang dicatat dari data baru, sedangkan pindah dicatat dari status penduduk.',
            'headers' => ['NIK', 'Nama', 'Jenis', 'Tanggal Catat', 'KK', 'Alamat'],
            'rows' => $residents->map(fn (Resident $resident) => [
                $resident->nik,
                $resident->name,
                $resident->status === 'Pindah' ? 'Pindah' : 'Datang',
                $resident->created_at?->format('d-m-Y'),
                $resident->kk,
                $resident->address . ' RT ' . $resident->rt . '/RW ' . $resident->rw,
            ])->all(),
            'summary' => [
                'Total Data' => $residents->count(),
                'Datang' => $residents->where('status', 'Aktif')->count(),
                'Pindah' => $residents->where('status', 'Pindah')->count(),
            ],
        ];
    }

    private function socialAssistanceReport(array $filters): array
    {
        $histories = SocialAssistanceHistory::query()
            ->with(['recipient.resident', 'recipient.category'])
            ->whereBetween('distributed_at', [$filters['start_date'], $filters['end_date']])
            ->whereHas('recipient.resident', function ($query) use ($filters) {
                $query
                    ->when($filters['rt'], fn ($query) => $query->where('rt', $filters['rt']))
                    ->when($filters['rw'], fn ($query) => $query->where('rw', $filters['rw']));
            })
            ->latest('distributed_at')
            ->get();

        return [
            'title' => 'Laporan Bantuan Sosial',
            'description' => 'Rekap riwayat penyaluran bantuan sosial berdasarkan tanggal distribusi.',
            'headers' => ['Tanggal', 'Penerima', 'NIK', 'Kategori', 'Periode', 'Nominal', 'Status'],
            'rows' => $histories->map(fn (SocialAssistanceHistory $history) => [
                $history->distributed_at?->format('d-m-Y'),
                $history->recipient->resident->name,
                $history->recipient->resident->nik,
                $history->recipient->category->name,
                $history->period ?: '-',
                'Rp ' . number_format((float) $history->amount, 0, ',', '.'),
                $history->status,
            ])->all(),
            'summary' => [
                'Total Transaksi' => $histories->count(),
                'Total Nominal' => 'Rp ' . number_format((float) $histories->sum('amount'), 0, ',', '.'),
                'Penerima Unik' => $histories->pluck('social_assistance_recipient_id')->unique()->count(),
            ],
        ];
    }
}
