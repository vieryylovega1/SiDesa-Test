@extends('layouts.app')

@section('title', 'User & Hak Akses - SiDesa')
@section('eyebrow', 'Keamanan Sistem')
@section('page-title', 'User & Hak Akses')

@section('actions')
    <a href="{{ route('users.create') }}" class="btn btn-success"><i class="bi bi-person-plus me-1"></i> Tambah User</a>
@endsection

@section('content')
    <div class="panel">
        <h5 class="fw-bold mb-1">Daftar User</h5>
        <div class="small muted mb-3">Kelola akun dan peran pengguna SiDesa.</div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roleLabel() }}</td>
                        <td><span class="badge-soft {{ $user->is_active ? 'badge-check' : 'badge-danger-soft' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-success"><i class="bi bi-pencil"></i></a>
                                @if (auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
@endsection
