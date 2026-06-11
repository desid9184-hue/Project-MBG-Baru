@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('admin.users') }}" class="nav-link active"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="{{ route('admin.orders') }}" class="nav-link"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1>Manajemen User</h1>
        <p>Kelola semua akun pengguna sistem MBG.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah User
    </a>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin"   {{ request('role') == 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="guru"    {{ request('role') == 'guru'    ? 'selected' : '' }}>Guru</option>
                    <option value="asisten" {{ request('role') == 'asisten' ? 'selected' : '' }}>Asisten</option>
                    <option value="driver"  {{ request('role') == 'driver'  ? 'selected' : '' }}>Driver</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sekolah/HP</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        // Logika warna dipisah sepenuhnya agar VS Code tidak bingung
                        $avatarBg = match($user->role) {
                            'admin'   => '#ef4444',
                            'guru'    => '#2563eb',
                            'asisten' => '#10b981',
                            default   => '#f59e0b',
                        };
                        $avatarStyle = "width:34px; height:34px; background-color: {$avatarBg}; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:.8rem; flex-shrink:0;";
                    @endphp
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                               <div @style([$avatarStyle])>
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <span class="fw-600">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge {{ $user->role_badge }}">{{ $user->role_label }}</span></td>
                        <td>
                            <div style="font-size:.8rem;">
                                @if($user->school) <div>{{ $user->school }}</div> @endif
                                @if($user->phone)  <div class="text-muted">{{ $user->phone }}</div> @endif
                                @if(!$user->school && !$user->phone) <span class="text-muted">-</span> @endif
                            </div>
                        </td>
                        <td style="font-size:.8rem;color:#64748b;">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada user ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection