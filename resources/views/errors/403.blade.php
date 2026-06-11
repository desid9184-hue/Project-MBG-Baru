<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-box { text-align: center; padding: 48px; max-width: 440px; }
        .error-icon { font-size: 5rem; color: #ef4444; margin-bottom: 16px; }
        h1 { font-size: 3rem; font-weight: 800; color: #0f172a; }
        p { color: #64748b; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h1>403</h1>
        <h4 class="fw-700 mb-2">Akses Ditolak</h4>
        <p class="mb-4">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login Ulang</a>
    </div>
</body>
</html>
