@props([
    'icon' => 'bi-inbox',
    'title' => 'Belum ada data',
    'message' => 'Data akan muncul setelah ditambahkan.',
])

<div class="text-center muted py-5">
    <i class="bi {{ $icon }} fs-1 d-block mb-3 text-success"></i>
    <div class="fw-bold text-body">{{ $title }}</div>
    <div class="small">{{ $message }}</div>
</div>
