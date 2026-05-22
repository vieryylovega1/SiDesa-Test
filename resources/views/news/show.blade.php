@extends('layouts.app')

@section('title', $post->title . ' - SiDesa')
@section('eyebrow', 'Informasi Publik')
@section('page-title', $post->title)

@section('actions')
    <div class="d-flex gap-2">
        @if ($post->status === 'published')
            <a href="{{ route('news.public.show', $post) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-globe me-1"></i> Publik</a>
        @endif
        <a href="{{ route('news.edit', $post) }}" class="btn btn-success"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-8">
            <article class="panel">
                @if ($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-100 mb-3" style="max-height:360px;object-fit:cover;border-radius:8px;">
                @endif
                <div class="d-flex gap-2 mb-3">
                    <span class="badge-soft badge-process">{{ $post->category->name }}</span>
                    <span class="badge-soft {{ $post->status === 'published' ? 'badge-check' : 'badge-wait' }}">{{ $post->status }}</span>
                </div>
                <p class="muted">{{ $post->excerpt }}</p>
                <div style="white-space: pre-line;">{{ $post->content }}</div>
            </article>
        </div>
        <div class="col-xl-4">
            <div class="panel mb-4">
                <h5 class="fw-bold mb-3">Komentar</h5>
                <div class="d-grid gap-3">
                    @forelse ($post->comments as $comment)
                        <div class="border rounded-3 p-3">
                            <div class="fw-semibold">{{ $comment->name }}</div>
                            <div class="small muted mb-2">{{ $comment->created_at->translatedFormat('d M Y H:i') }} - {{ $comment->status }}</div>
                            <p class="mb-3">{{ $comment->comment }}</p>
                            @if ($comment->status === 'pending')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('news-comments.approve', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('news-comments.reject', $comment) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-danger">Tolak</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="muted">Belum ada komentar.</div>
                    @endforelse
                </div>
            </div>

            <form method="POST" action="{{ route('news.destroy', $post) }}" class="panel" onsubmit="return confirm('Hapus berita ini?')">
                @csrf
                @method('DELETE')
                <h5 class="fw-bold mb-2">Hapus Berita</h5>
                <p class="muted">Berita dan komentarnya akan dihapus.</p>
                <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i> Hapus</button>
            </form>
        </div>
    </div>
@endsection
