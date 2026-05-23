@extends('layouts.app')

@section('title', 'Agenda Desa - SiDesa')
@section('eyebrow', 'Agenda dan kondisi lapangan')
@section('page-title', 'Agenda Desa')

@push('styles')
    <style>
        .agenda-hero {
            background:
                linear-gradient(120deg, rgba(13, 63, 56, .92), rgba(23, 107, 91, .78)),
                url("https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=1600&q=80");
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            color: #fff;
            min-height: 240px;
            padding: 28px;
            display: flex;
            align-items: end;
        }

        .agenda-hero p {
            max-width: 760px;
            color: rgba(255, 255, 255, .82);
        }

        .agenda-stat,
        .agenda-card,
        .comparison-panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .agenda-stat {
            padding: 18px;
            height: 100%;
        }

        .agenda-stat .icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(54, 185, 145, .14);
            color: var(--primary);
            font-size: 1.25rem;
        }

        .agenda-card {
            padding: 18px;
            height: 100%;
        }

        .agenda-card.good {
            border-left: 5px solid #16a34a;
        }

        .agenda-card.warning {
            border-left: 5px solid #f59e0b;
        }

        .agenda-date-box {
            min-width: 74px;
            border-radius: 8px;
            padding: 12px 10px;
            text-align: center;
            font-weight: 800;
            background: var(--soft);
            color: var(--primary);
        }

        .comparison-panel {
            padding: 20px;
        }

        .comparison-item {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 14px;
            background: var(--soft);
        }

        .agenda-filter .btn {
            border-radius: 8px;
        }

        [data-theme="dark"] .text-warning {
            color: #fbbf24 !important;
        }
    </style>
@endpush

@section('content')
    <section class="agenda-hero mb-4">
        <div>
            <span class="badge text-bg-warning mb-3">Agenda Operasional Desa</span>
            <h1 class="fw-bold mb-2">Kabar baik dan hal yang perlu perhatian.</h1>
            <p class="mb-0">Halaman ini membantu perangkat desa melihat agenda positif yang perlu dipublikasikan dan agenda perhatian yang harus segera ditindaklanjuti.</p>
        </div>
    </section>

    <section class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="agenda-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="small muted">Total Agenda</div>
                        <div class="fs-2 fw-bold">{{ $summary['total'] }}</div>
                    </div>
                    <div class="icon"><i class="bi bi-calendar2-week"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="agenda-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="small muted">Kabar Baik</div>
                        <div class="fs-2 fw-bold text-success">{{ $summary['baik'] }}</div>
                    </div>
                    <div class="icon"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="agenda-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="small muted">Perlu Perhatian</div>
                        <div class="fs-2 fw-bold text-warning">{{ $summary['perhatian'] }}</div>
                    </div>
                    <div class="icon"><i class="bi bi-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="agenda-stat">
                <div class="d-flex justify-content-between gap-3">
                    <div>
                        <div class="small muted">Selesai</div>
                        <div class="fs-2 fw-bold">{{ $summary['selesai'] }}</div>
                    </div>
                    <div class="icon"><i class="bi bi-clipboard2-check"></i></div>
                </div>
            </div>
        </div>
    </section>

    <section class="comparison-panel mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Perbandingan Kondisi Desa</h5>
                <div class="small muted">Gunakan bagian ini sebagai bahan rapat mingguan atau presentasi laporan desa.</div>
            </div>
            <div class="agenda-filter btn-group" role="group">
                <button class="btn btn-success active" type="button" data-filter="all">Semua</button>
                <button class="btn btn-outline-success" type="button" data-filter="baik">Kabar Baik</button>
                <button class="btn btn-outline-warning" type="button" data-filter="perhatian">Perhatian</button>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="comparison-item h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge text-bg-success">Kabar Baik</span>
                        <span class="small muted">Layak dipublikasikan</span>
                    </div>
                    @foreach ($goodAgendas as $agenda)
                        <div class="mb-3">
                            <div class="fw-bold">{{ $agenda['judul'] }}</div>
                            <div class="small muted">{{ $agenda['dampak'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="comparison-item h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge text-bg-warning">Perlu Perhatian</span>
                        <span class="small muted">Perlu tindak lanjut</span>
                    </div>
                    @foreach ($badAgendas as $agenda)
                        <div class="mb-3">
                            <div class="fw-bold">{{ $agenda['judul'] }}</div>
                            <div class="small muted">{{ $agenda['dampak'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="row g-3" id="agendaList">
        @foreach ($agendas as $agenda)
            <div class="col-lg-6 agenda-item" data-category="{{ $agenda['kategori'] }}">
                <article class="agenda-card {{ $agenda['kategori'] === 'baik' ? 'good' : 'warning' }}">
                    <div class="d-flex gap-3">
                        <div class="agenda-date-box">
                            {{ $agenda['tanggal'] }}
                            <div class="small fw-semibold muted">{{ $agenda['waktu'] }}</div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="badge {{ $agenda['kategori'] === 'baik' ? 'text-bg-success' : 'text-bg-warning' }}">{{ $agenda['label'] }}</span>
                                <span class="badge-soft {{ $agenda['status'] === 'Prioritas' || $agenda['status'] === 'Butuh Validasi' ? 'badge-wait' : 'badge-process' }}">{{ $agenda['status'] }}</span>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $agenda['judul'] }}</h5>
                            <div class="small muted mb-3">
                                <i class="bi bi-geo-alt me-1"></i>{{ $agenda['lokasi'] }}
                                <span class="mx-2">.</span>
                                <i class="bi bi-person-badge me-1"></i>{{ $agenda['penanggung_jawab'] }}
                            </div>
                            <p class="mb-3">{{ $agenda['ringkasan'] }}</p>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="comparison-item h-100">
                                        <div class="small fw-bold mb-1">Dampak</div>
                                        <div class="small muted">{{ $agenda['dampak'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="comparison-item h-100">
                                        <div class="small fw-bold mb-1">Tindak Lanjut</div>
                                        <div class="small muted">{{ $agenda['tindak_lanjut'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        @endforeach
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-filter]').forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                document.querySelectorAll('[data-filter]').forEach((item) => {
                    item.className = item.dataset.filter === 'perhatian'
                        ? 'btn btn-outline-warning'
                        : 'btn btn-outline-success';
                });

                button.className = filter === 'perhatian'
                    ? 'btn btn-warning active'
                    : 'btn btn-success active';

                document.querySelectorAll('.agenda-item').forEach((item) => {
                    item.classList.toggle('d-none', filter !== 'all' && item.dataset.category !== filter);
                });
            });
        });
    </script>
@endpush
