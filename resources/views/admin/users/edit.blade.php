@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

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
            <li class="breadcrumb-item active">Edit {{ $user->name }}</li>
        </ol>
    </nav>
    <h1>Edit User: {{ $user->name }}</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-pencil-fill text-primary"></i> Form Edit User</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="admin"   {{ $user->role == 'admin'   ? 'selected' : '' }}>Administrator</option>
                            <option value="guru"    {{ $user->role == 'guru'    ? 'selected' : '' }}>Guru</option>
                            <option value="asisten" {{ $user->role == 'asisten' ? 'selected' : '' }}>Asisten Lapangan</option>
                            <option value="driver"  {{ $user->role == 'driver'  ? 'selected' : '' }}>Driver</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sekolah</label>
                        <input type="text" name="school" class="form-control" value="{{ old('school', $user->school) }}">
                    </div>
                    <hr>
                    <p class="text-muted" style="font-size:.85rem;">
                        <i class="bi bi-info-circle me-1"></i>Kosongkan password jika tidak ingin mengubah.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Update User
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
