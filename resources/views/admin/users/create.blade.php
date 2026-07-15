@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('admin.users') }}" class="nav-link active"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="{{ route('admin.orders') }}" class="nav-link"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
@endsection

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">User</a></li>
            <li class="breadcrumb-item active">Tambah Baru</li>
        </ol>
    </nav>
    <h1>Tambah User Baru</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-person-plus-fill text-primary"></i> Form User</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin"   {{ old('role') == 'admin'   ? 'selected' : '' }}>Administrator</option>
                            <option value="guru"    {{ old('role') == 'guru'    ? 'selected' : '' }}>Guru</option>
                            <option value="asisten" {{ old('role') == 'asisten' ? 'selected' : '' }}>Asisten Lapangan</option>
                            <option value="driver"  {{ old('role') == 'driver'  ? 'selected' : '' }}>Driver</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xx...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sekolah <span class="text-muted">(opsional, untuk Guru)</span></label>
                        <input type="text" name="school" class="form-control" value="{{ old('school') }}" placeholder="Nama sekolah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Simpan User
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
