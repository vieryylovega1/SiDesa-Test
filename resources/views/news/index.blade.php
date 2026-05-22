@extends('layouts.app')

@section('title', 'Berita Desa - SiDesa')
@section('eyebrow', 'Informasi Publik')
@section('page-title', 'Manajemen Berita Desa')

@section('actions')
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('news.public.index') }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-globe me-1"></i> Lihat Publik</a>
        <a href="{{ route('news.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Tulis Berita</a>
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="fw-bold mb-1">Daftar Berita</h5>
                <div class="small muted">Kelola posting, kategori, gambar, dan komentar warga.</div>
            </div>
            <form class="d-flex gap-2" method="GET" action="{{ route('news.index') }}">
                <input type="search" name="cari" value="{{ $search }}" class="form-control" placeholder="Cari judul berita">
                <button class="btn btn-outline-success"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $post->title }}</div>
                            <div class="small muted">{{ $post->author?->name ?? 'SiDesa' }}</div>
                        </td>
                        <td>{{ $post->category->name }}</td>
                        <td><span class="badge-soft {{ $post->status === 'published' ? 'badge-check' : 'badge-wait' }}">{{ $post->status === 'published' ? 'Published' : 'Draft' }}</span></td>
                        <td>{{ $post->comments_count }}</td>
                        <td class="muted">{{ $post->published_at?->translatedFormat('d M Y') ?? $post->created_at->translatedFormat('d M Y') }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('news.show', $post) }}" class="btn btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('news.edit', $post) }}" class="btn btn-outline-success"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted py-5">Belum ada berita desa.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $posts->links() }}
    </div>
@endsection
