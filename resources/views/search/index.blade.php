@extends('layouts.app')

@section('title', 'Pencarian Global - SiDesa')
@section('eyebrow', 'Pencarian')
@section('page-title', 'Pencarian Global')

@section('content')
    <div class="panel mb-4">
        <h5 class="fw-bold mb-1">Hasil Pencarian</h5>
        <div class="muted">Kata kunci: <strong>{{ $query ?: '-' }}</strong></div>
    </div>

    @if ($query === '')
        <div class="panel text-center py-5">
            <i class="bi bi-search fs-1 text-success"></i>
            <h5 class="fw-bold mt-3">Masukkan kata kunci</h5>
            <div class="muted">Cari NIK, nama warga, nomor KK, surat, berita, atau pengaduan dari kolom pencarian atas.</div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="panel h-100">
                    <h5 class="fw-bold mb-3">Penduduk</h5>
                    <div class="d-grid gap-2">
                        @forelse ($residents as $resident)
                            <a href="{{ route('residents.show', $resident) }}" class="quick-result">
                                <strong>{{ $resident->name }}</strong>
                                <span>NIK {{ $resident->nik }} | KK {{ $resident->kk }}</span>
                            </a>
                        @empty
                            <div class="muted">Tidak ada data penduduk.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel h-100">
                    <h5 class="fw-bold mb-3">Kartu Keluarga</h5>
                    <div class="d-grid gap-2">
                        @forelse ($familyCards as $familyCard)
                            <a href="{{ route('family-cards.show', $familyCard) }}" class="quick-result">
                                <strong>KK {{ $familyCard->number }}</strong>
                                <span>{{ $familyCard->head_name }} | RT {{ $familyCard->rt }}/RW {{ $familyCard->rw }}</span>
                            </a>
                        @empty
                            <div class="muted">Tidak ada data KK.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel h-100">
                    <h5 class="fw-bold mb-3">Surat</h5>
                    <div class="d-grid gap-2">
                        @forelse ($letters as $letter)
                            <a href="{{ route('letters.show', $letter) }}" class="quick-result">
                                <strong>{{ $letter->applicant_name }}</strong>
                                <span>{{ $letter->letter_type }} | {{ $letter->letter_number ?: $letter->status }}</span>
                            </a>
                        @empty
                            <div class="muted">Tidak ada data surat.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel h-100">
                    <h5 class="fw-bold mb-3">Pengaduan & Berita</h5>
                    <div class="d-grid gap-2">
                        @foreach ($complaints as $complaint)
                            <a href="{{ route('complaints.show', $complaint) }}" class="quick-result">
                                <strong>{{ $complaint->title }}</strong>
                                <span>{{ $complaint->ticket_number }} | {{ $complaint->status }}</span>
                            </a>
                        @endforeach
                        @foreach ($posts as $post)
                            <a href="{{ route('news.show', $post) }}" class="quick-result">
                                <strong>{{ $post->title }}</strong>
                                <span>Berita Desa | {{ $post->status }}</span>
                            </a>
                        @endforeach
                        @if ($complaints->isEmpty() && $posts->isEmpty())
                            <div class="muted">Tidak ada pengaduan atau berita.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .quick-result {
            display: grid;
            gap: 2px;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            color: var(--ink);
            text-decoration: none;
            background: var(--soft);
        }

        .quick-result span {
            color: var(--muted);
            font-size: .875rem;
        }

        .quick-result:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
    </style>
@endpush
