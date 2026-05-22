<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SiDesa')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #176b5b;
            --primary-dark: #0d3f38;
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #e5e7eb;
            --soft: #f7faf9;
            --page: #f3f6f5;
            --panel: #ffffff;
            --sidebar: linear-gradient(180deg, #0d3f38, #176b5b);
            --shadow: 0 18px 45px rgba(17, 24, 39, .05);
        }

        [data-theme="dark"] {
            --primary: #36b991;
            --primary-dark: #114f45;
            --ink: #e5e7eb;
            --muted: #9ca3af;
            --line: #243447;
            --soft: #111827;
            --page: #0b1220;
            --panel: #111827;
            --sidebar: linear-gradient(180deg, #07111f, #0f3c35);
            --shadow: 0 18px 45px rgba(0, 0, 0, .25);
        }

        body {
            background: var(--page);
            color: var(--ink);
            font-family: "Inter", "Segoe UI", system-ui, sans-serif;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
            transition: grid-template-columns .2s ease;
        }

        body.sidebar-collapsed .app-shell {
            grid-template-columns: 86px 1fr;
        }

        .sidebar {
            background: var(--sidebar);
            color: #fff;
            padding: 28px 22px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1020;
        }

        body.sidebar-collapsed .sidebar {
            padding-inline: 16px;
        }

        body.sidebar-collapsed .sidebar-label,
        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .user-panel {
            display: none !important;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
        }

        .nav-link {
            color: rgba(255, 255, 255, .78);
            border-radius: 8px;
            padding: 11px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .13);
        }

        .content {
            padding: 28px;
        }

        .panel,
        .topbar {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .topbar,
        .panel {
            padding: 22px;
        }

        .muted {
            color: var(--muted);
        }

        .btn-success {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-success:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .icon-btn {
            width: 42px;
            height: 42px;
            display: inline-grid;
            place-items: center;
            padding: 0;
        }

        .table > :not(caption) > * > * {
            padding: 14px 12px;
            background: transparent;
            color: var(--ink);
        }

        .table,
        .breadcrumb,
        .form-label {
            color: var(--ink);
        }

        .badge-soft {
            border-radius: 999px;
            padding: 7px 10px;
            font-weight: 700;
            font-size: .76rem;
        }

        .badge-process { background: #e8f4f1; color: #176b5b; }
        .badge-check { background: #ecfdf5; color: #15803d; }
        .badge-wait { background: #fff7ed; color: #c2410c; }
        .badge-danger-soft { background: #fef2f2; color: #b91c1c; }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 11px 12px;
            background-color: var(--panel);
            border-color: var(--line);
            color: var(--ink);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        .select2-container--bootstrap-5 .select2-selection {
            background-color: var(--panel);
            border-color: var(--line);
            color: var(--ink);
            border-radius: 8px;
            min-height: 46px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--ink);
        }

        .dataTables_wrapper .form-control,
        .dataTables_wrapper .form-select {
            min-height: 38px;
            padding: 7px 10px;
        }

        .global-search {
            min-width: min(420px, 45vw);
        }

        .mobile-menu-btn {
            display: none;
        }

        .toast-container {
            z-index: 1090;
        }

        @media (max-width: 991.98px) {
            .app-shell {
                display: block;
            }

            .sidebar {
                height: 100vh;
                position: fixed;
                left: 0;
                width: min(320px, 86vw);
                padding: 18px;
                transform: translateX(-100%);
                transition: transform .2s ease;
            }

            body.mobile-sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-collapsed .sidebar-label,
            body.sidebar-collapsed .brand-text,
            body.sidebar-collapsed .user-panel {
                display: block !important;
            }

            .sidebar .nav {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .content {
                padding: 18px;
            }

            .mobile-menu-btn {
                display: inline-grid;
            }

            .global-search {
                min-width: 100%;
                order: 3;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="brand-mark"><i class="bi bi-buildings fs-4"></i></div>
            <div class="brand-text">
                <div class="fw-bold fs-4">SiDesa</div>
                <div class="small text-white-50">Sistem Informasi Desa</div>
            </div>
        </div>

        <nav class="nav flex-column gap-1">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i> <span class="sidebar-label">Dashboard</span></a>
            @if (auth()->user()?->canAccess('penduduk.view'))
                <a class="nav-link {{ request()->routeIs('residents.*') ? 'active' : '' }}" href="{{ route('residents.index') }}"><i class="bi bi-people"></i> <span class="sidebar-label">Penduduk</span></a>
            @endif
            @if (auth()->user()?->canAccess('kk.view'))
                <a class="nav-link {{ request()->routeIs('family-cards.*') ? 'active' : '' }}" href="{{ route('family-cards.index') }}"><i class="bi bi-postcard-heart"></i> <span class="sidebar-label">Kartu Keluarga</span></a>
            @endif
            @if (auth()->user()?->canAccess('surat.view'))
                <a class="nav-link {{ request()->routeIs('letters.*') ? 'active' : '' }}" href="{{ route('letters.index') }}"><i class="bi bi-file-earmark-text"></i> <span class="sidebar-label">Layanan Surat</span></a>
            @endif
            @if (auth()->user()?->canAccess('berita.view') || auth()->user()?->canAccess('berita.manage'))
                <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ auth()->user()?->canAccess('berita.manage') ? route('news.index') : route('news.public.index') }}"><i class="bi bi-newspaper"></i> <span class="sidebar-label">Berita Desa</span></a>
            @endif
            @if (auth()->user()?->canAccess('bantuan.view'))
                <a class="nav-link {{ request()->routeIs('social-assistance.*') ? 'active' : '' }}" href="{{ route('social-assistance.index') }}"><i class="bi bi-heart-pulse"></i> <span class="sidebar-label">Bantuan Sosial</span></a>
            @endif
            @if (auth()->user()?->canAccess('complaints.view'))
                <a class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}" href="{{ route('complaints.index') }}"><i class="bi bi-chat-square-text"></i> <span class="sidebar-label">Pengaduan</span></a>
            @endif
            <a class="nav-link" href="#"><i class="bi bi-calendar-event"></i> <span class="sidebar-label">Agenda</span></a>
            <a class="nav-link" href="#"><i class="bi bi-clipboard-data"></i> <span class="sidebar-label">Laporan</span></a>
            @if (auth()->user()?->canAccess('users.manage'))
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-shield-lock"></i> <span class="sidebar-label">User & Akses</span></a>
            @endif
        </nav>

        <div class="user-panel mt-4 p-3 rounded-3" style="background: rgba(255,255,255,.12);">
            <div class="small text-white-50">Login sebagai</div>
            <div class="fw-bold">{{ auth()->user()?->name }}</div>
            <div class="small text-white-50 mt-1">{{ auth()->user()?->roleLabel() }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="btn btn-sm btn-light w-100"><i class="bi bi-box-arrow-right me-1"></i> Keluar</button>
            </form>
        </div>
    </aside>

    <main class="content">
        <div class="topbar d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <button class="btn btn-outline-secondary icon-btn mobile-menu-btn" id="mobileSidebarToggle" type="button" title="Menu">
                <i class="bi bi-list"></i>
            </button>
            <button class="btn btn-outline-secondary icon-btn d-none d-lg-inline-grid" id="sidebarToggle" type="button" title="Ciutkan sidebar">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>
            <form method="GET" action="{{ route('global-search') }}" class="global-search">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari penduduk, NIK, surat, berita, pengaduan">
                </div>
            </form>
            <div>
                <div class="small muted">@yield('eyebrow', 'Administrasi Desa')</div>
                <h4 class="mb-0 fw-bold">@yield('page-title', 'SiDesa')</h4>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-secondary icon-btn" id="themeToggle" type="button" title="Dark mode"><i class="bi bi-moon-stars"></i></button>
                @yield('actions')
            </div>
        </div>

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('page-title', 'SiDesa')</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <div class="fw-bold mb-1">Data belum lengkap</div>
                Periksa kembali isian yang ditandai.
            </div>
        @endif

        @yield('content')
    </main>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    @if (session('success'))
        <div class="toast align-items-center text-bg-success border-0" role="alert" data-bs-delay="4500">
            <div class="d-flex">
                <div class="toast-body">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="toast align-items-center text-bg-danger border-0" role="alert" data-bs-delay="5500">
            <div class="d-flex">
                <div class="toast-body">Data belum lengkap. Periksa kembali isian yang ditandai.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body d-flex align-items-center gap-3 p-4">
                <div class="spinner-border text-success"></div>
                <div>
                    <div class="fw-bold">Memproses data</div>
                    <div class="small muted">Mohon tunggu sebentar.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const savedTheme = localStorage.getItem('sidesa-theme');
    if (savedTheme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    if (localStorage.getItem('sidesa-sidebar') === 'collapsed') document.body.classList.add('sidebar-collapsed');

    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidesa-sidebar', document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
    });

    document.getElementById('mobileSidebarToggle')?.addEventListener('click', () => {
        document.body.classList.toggle('mobile-sidebar-open');
    });

    document.getElementById('themeToggle')?.addEventListener('click', () => {
        const nextTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', nextTheme);
        localStorage.setItem('sidesa-theme', nextTheme);
    });

    document.querySelectorAll('.toast').forEach((toast) => new bootstrap.Toast(toast).show());

    $(function () {
        $('select.form-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            minimumResultsForSearch: 8
        });

        $('table.table').each(function () {
            if ($(this).closest('.dataTables_wrapper').length || $(this).find('tbody tr').length < 2) return;

            new DataTable(this, {
                responsive: true,
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                language: {
                    emptyTable: 'Belum ada data.',
                    zeroRecords: 'Data tidak ditemukan.'
                }
            });
        });
    });

    document.addEventListener('submit', function (event) {
        const form = event.target;
        const methodInput = form.querySelector('input[name="_method"]');
        const isDelete = methodInput && methodInput.value.toUpperCase() === 'DELETE';

        if (isDelete && !form.dataset.confirmed) {
            event.preventDefault();
            event.stopImmediatePropagation();

            Swal.fire({
                title: 'Hapus data?',
                text: 'Data yang dihapus tidak bisa dikembalikan dari halaman ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#176b5b'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        }
    }, true);

    document.querySelectorAll('form:not([method="GET"])').forEach((form) => {
        form.addEventListener('submit', () => {
            if (form.dataset.confirmed === 'true' || form.querySelector('input[name="_method"][value="DELETE"]')) return;
            bootstrap.Modal.getOrCreateInstance(document.getElementById('loadingModal')).show();
        });
    });
</script>
@stack('scripts')
</body>
</html>
