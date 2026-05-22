@extends('layouts.app')

@section('title', 'Dashboard - SiDesa')
@section('eyebrow', now()->translatedFormat('l, d F Y'))
@section('page-title', 'Dashboard')

@section('actions')
    @if (auth()->user()?->canAccess('penduduk.manage'))
        <a href="{{ route('residents.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Input Data</a>
    @endif
@endsection

@push('styles')
    <style>
        .hero {
            background:
                linear-gradient(120deg, rgba(13, 63, 56, .94), rgba(23, 107, 91, .84)),
                url("https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80");
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            min-height: 260px;
            color: #fff;
            padding: 32px;
            display: flex;
            align-items: end;
        }

        .hero h1 {
            font-size: clamp(2rem, 4vw, 4.2rem);
            line-height: 1;
            margin: 0;
            letter-spacing: 0;
        }

        .hero p {
            max-width: 720px;
            color: rgba(255, 255, 255, .82);
            margin: 14px 0 0;
        }

        .stat-card,
        .service-card,
        .mini-stat {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .stat-card {
            padding: 20px;
            height: 100%;
        }

        .stat-icon,
        .service-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: #e8f4f1;
            color: var(--primary);
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 800;
            margin-top: 14px;
        }

        .chart-box {
            position: relative;
            height: 300px;
        }

        .mini-stat {
            padding: 14px;
            background: var(--soft);
        }

        .quick-action {
            border: 1px solid var(--line);
            color: var(--ink);
            background: var(--panel);
            border-radius: 8px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 700;
        }

        .quick-action:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .agenda-date {
            min-width: 62px;
            border-radius: 8px;
            background: var(--soft);
            color: var(--primary);
            text-align: center;
            padding: 10px 8px;
            font-weight: 800;
        }
    </style>
@endpush

@section('content')
    <section class="hero mb-4">
        <div>
            <span class="badge text-bg-warning mb-3">Portal Administrasi Desa</span>
            <h1>Kelola layanan desa lebih cepat dan tertata.</h1>
            <p>SiDesa membantu perangkat desa memantau data penduduk, layanan surat, agenda, pengaduan, bantuan sosial, dan laporan dalam satu dashboard.</p>
        </div>
    </section>

    <section class="row g-3 mb-4">
        @foreach ($stats as $stat)
            <div class="col-md-6 col-xl-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <div class="small muted">{{ $stat['label'] }}</div>
                            <div class="stat-value">{{ $stat['value'] }}</div>
                            <div class="small muted">{{ $stat['trend'] }}</div>
                        </div>
                        <div class="stat-icon"><i class="bi {{ $stat['icon'] }}"></i></div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="panel h-100">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Grafik Penduduk</h5>
                        <div class="small muted" id="stats-updated">Update: {{ $statistics['updated_at'] }}</div>
                    </div>
                </div>
                <div class="chart-box"><canvas id="residentTrendChart"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="panel h-100">
                <h5 class="fw-bold mb-3">Komposisi Gender</h5>
                <div class="chart-box"><canvas id="genderChart"></canvas></div>
            </div>
        </div>
    </section>

    <section class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="panel h-100">
                <h5 class="fw-bold mb-3">Statistik Pekerjaan</h5>
                <div class="chart-box"><canvas id="occupationChart"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="panel h-100">
                <h5 class="fw-bold mb-3">Statistik Pendidikan</h5>
                <div class="chart-box"><canvas id="educationChart"></canvas></div>
            </div>
        </div>
    </section>

    <section class="row g-4">
        <div class="col-xl-8">
            <div class="panel mb-4">
                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Permohonan Surat Terbaru</h5>
                        <div class="small muted">Pantau proses layanan warga secara cepat.</div>
                    </div>
                    <a href="{{ route('letters.index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Nama Warga</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($letters as $letter)
                            @php
                                $badge = match ($letter->status) {
                                    'Selesai' => 'badge-check',
                                    'Verifikasi', 'Diajukan' => 'badge-wait',
                                    'Ditolak' => 'badge-danger-soft',
                                    default => 'badge-process',
                                };
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $letter->applicant_name }}</td>
                                <td>{{ $letter->letter_type }}</td>
                                <td class="muted">{{ $letter->requested_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td><span class="badge-soft {{ $badge }}">{{ $letter->status }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center muted py-5">Belum ada permohonan surat.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="panel mb-4">
                <h5 class="fw-bold mb-1">Aksi Cepat</h5>
                <div class="small muted mb-3">Menu yang sering dipakai operator.</div>
                <div class="d-grid gap-2">
                    @if (auth()->user()?->canAccess('penduduk.manage'))
                        <a class="quick-action" href="{{ route('residents.create') }}"><i class="bi bi-person-plus text-success"></i> Tambah Penduduk</a>
                        <a class="quick-action" href="{{ route('letters.create') }}"><i class="bi bi-file-earmark-plus text-success"></i> Buat Surat Baru</a>
                    @endif
                    @if (auth()->user()?->canAccess('complaints.view'))
                        <a class="quick-action" href="{{ route('complaints.index') }}"><i class="bi bi-chat-square-text text-success"></i> Pantau Pengaduan</a>
                    @endif
                    @if (auth()->user()?->canAccess('bantuan.view'))
                        <a class="quick-action" href="{{ route('social-assistance.index') }}"><i class="bi bi-heart-pulse text-success"></i> Data Bantuan</a>
                    @endif
                </div>
            </div>

            <div class="panel">
                <h5 class="fw-bold mb-1">Agenda Desa</h5>
                <div class="small muted mb-3">Kegiatan terdekat bulan ini.</div>
                <div class="d-grid gap-3">
                    @foreach ($agendas as $agenda)
                        <div class="d-flex gap-3">
                            <div class="agenda-date">{{ $agenda['tanggal'] }}</div>
                            <div>
                                <div class="fw-bold">{{ $agenda['judul'] }}</div>
                                <div class="small muted"><i class="bi bi-geo-alt me-1"></i>{{ $agenda['lokasi'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        const initialStatistics = @json($statistics);
        const palette = ['#176b5b', '#f2b84b', '#2f80ed', '#9b51e0', '#eb5757', '#27ae60', '#f2994a', '#4f4f4f'];

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }
        };

        const labels = (items) => items.map((item) => item.label);
        const totals = (items) => items.map((item) => item.total);
        const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(value);

        const residentTrendChart = new Chart(document.getElementById('residentTrendChart'), {
            type: 'line',
            data: {
                labels: labels(initialStatistics.monthly),
                datasets: [{
                    label: 'Penduduk baru',
                    data: totals(initialStatistics.monthly),
                    borderColor: '#176b5b',
                    backgroundColor: 'rgba(23, 107, 91, .14)',
                    fill: true,
                    tension: .35,
                    pointRadius: 4
                }]
            },
            options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        const genderChart = new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: { labels: labels(initialStatistics.gender), datasets: [{ data: totals(initialStatistics.gender), backgroundColor: ['#176b5b', '#f2b84b'] }] },
            options: chartOptions
        });

        const occupationChart = new Chart(document.getElementById('occupationChart'), {
            type: 'bar',
            data: { labels: labels(initialStatistics.occupation), datasets: [{ label: 'Penduduk', data: totals(initialStatistics.occupation), backgroundColor: palette }] },
            options: { ...chartOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        const educationChart = new Chart(document.getElementById('educationChart'), {
            type: 'bar',
            data: { labels: labels(initialStatistics.education), datasets: [{ label: 'Penduduk', data: totals(initialStatistics.education), backgroundColor: palette.slice().reverse() }] },
            options: { ...chartOptions, indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
        });

        function updateChart(chart, items) {
            chart.data.labels = labels(items);
            chart.data.datasets[0].data = totals(items);
            chart.update();
        }

        async function refreshStatistics() {
            try {
                const response = await fetch('{{ route('dashboard.statistics') }}', { headers: { 'Accept': 'application/json' } });
                if (!response.ok) return;
                const statistics = await response.json();
                updateChart(residentTrendChart, statistics.monthly);
                updateChart(genderChart, statistics.gender);
                updateChart(occupationChart, statistics.occupation);
                updateChart(educationChart, statistics.education);
                document.getElementById('stats-updated').textContent = `Update: ${statistics.updated_at}`;
            } catch (error) {
                console.warn('Statistik dashboard belum bisa diperbarui.', error);
            }
        }

        setInterval(refreshStatistics, 30000);
    </script>
@endpush
