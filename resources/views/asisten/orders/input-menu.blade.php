@extends('layouts.app')

@section('title', 'Input Menu')
@section('page-title', 'Input Menu & Gizi')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('asisten.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('asisten.orders') }}" class="nav-link active"><i class="bi bi-clipboard2-list-fill"></i> Kelola Pesanan</a>
@endsection

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('asisten.orders') }}">Pesanan</a></li>
            <li class="breadcrumb-item"><a href="{{ route('asisten.orders.show', $order) }}">#{{ $order->id }}</a></li>
            <li class="breadcrumb-item active">Input Menu</li>
        </ol>
    </nav>
    <h1>Input Menu & Kandungan Gizi</h1>
    <p>Pesanan #{{ $order->id }} — {{ $order->guru->name }} ({{ $order->tanggal_pengiriman->format('d M Y') }})</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Form Menu Makanan</h5>
                @if($menu)
                <span class="badge bg-info">Edit Mode</span>
                @else
                <span class="badge bg-success">Input Baru</span>
                @endif
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('asisten.orders.menu.store', $order) }}">
                    @csrf

                    <!-- Komponen Menu -->
                    <h6 class="fw-700 mb-3 text-primary">
                        <i class="bi bi-basket-fill me-2"></i>Komponen Makanan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Lauk <span class="text-danger">*</span></label>
                            <input type="text" name="lauk"
                                   class="form-control @error('lauk') is-invalid @enderror"
                                   value="{{ old('lauk', $menu->lauk ?? '') }}"
                                   placeholder="Contoh: Ayam Goreng Bumbu Kuning" required>
                            @error('lauk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sayur <span class="text-danger">*</span></label>
                            <input type="text" name="sayur"
                                   class="form-control @error('sayur') is-invalid @enderror"
                                   value="{{ old('sayur', $menu->sayur ?? '') }}"
                                   placeholder="Contoh: Tumis Kangkung Bawang" required>
                            @error('sayur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buah <span class="text-danger">*</span></label>
                            <input type="text" name="buah"
                                   class="form-control @error('buah') is-invalid @enderror"
                                   value="{{ old('buah', $menu->buah ?? '') }}"
                                   placeholder="Contoh: Pisang Ambon" required>
                            @error('buah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sambal <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="sambal"
                                   class="form-control @error('sambal') is-invalid @enderror"
                                   value="{{ old('sambal', $menu->sambal ?? '') }}"
                                   placeholder="Contoh: Sambal Terasi">
                            @error('sambal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Kandungan Gizi -->
                    <div class="p-3 mb-4" style="background:#f0fdf4;border-radius:12px;border:1.5px solid #bbf7d0;">
                        <h6 class="fw-700 mb-3 text-success">
                            <i class="bi bi-bar-chart-fill me-2"></i>Kandungan Gizi per Porsi
                        </h6>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="form-label">Kalori (kkal) <span class="text-danger">*</span></label>
                                <input type="number" name="kalori" step="0.01"
                                       class="form-control @error('kalori') is-invalid @enderror"
                                       value="{{ old('kalori', $menu->kalori ?? '') }}"
                                       placeholder="0" min="0" max="9999" required>
                                @error('kalori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Protein (g) <span class="text-danger">*</span></label>
                                <input type="number" name="protein" step="0.1"
                                       class="form-control @error('protein') is-invalid @enderror"
                                       value="{{ old('protein', $menu->protein ?? '') }}"
                                       placeholder="0" min="0" max="999" required>
                                @error('protein')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Lemak (g) <span class="text-danger">*</span></label>
                                <input type="number" name="lemak" step="0.1"
                                       class="form-control @error('lemak') is-invalid @enderror"
                                       value="{{ old('lemak', $menu->lemak ?? '') }}"
                                       placeholder="0" min="0" max="999" required>
                                @error('lemak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Karbohidrat (g) <span class="text-danger">*</span></label>
                                <input type="number" name="karbohidrat" step="0.1"
                                       class="form-control @error('karbohidrat') is-invalid @enderror"
                                       value="{{ old('karbohidrat', $menu->karbohidrat ?? '') }}"
                                       placeholder="0" min="0" max="999" required>
                                @error('karbohidrat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Kalori Preview -->
                        <div class="mt-3 p-2" style="background:white;border-radius:8px;text-align:center;">
                            <small class="text-muted">Estimasi Total Kalori (per porsi):</small>
                            <div class="fw-800 text-success fs-5" id="kalori-preview">0 kkal</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Catatan khusus, alergen, atau informasi tambahan...">{{ old('keterangan', $menu->keterangan ?? '') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle-fill me-2"></i>Simpan Menu & Gizi
                        </button>
                        <a href="{{ route('asisten.orders.show', $order) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('kalori').addEventListener('input', updateKalori);
function updateKalori() {
    const val = parseFloat(document.getElementById('kalori').value) || 0;
    document.getElementById('kalori-preview').textContent = val.toLocaleString('id-ID') + ' kkal';
}
// Rename input id references
document.addEventListener('DOMContentLoaded', () => {
    const kaloriInput = document.querySelector('input[name="kalori"]');
    if (kaloriInput) {
        kaloriInput.setAttribute('id', 'kalori');
        kaloriInput.addEventListener('input', updateKalori);
        updateKalori.call(kaloriInput);
    }
});
</script>
@endpush
