<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem MBG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 450px; /* Lebar disesuaikan agar form proporsional di tengah */
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.3);
        }
        .login-right {
            flex: 1;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Styling Header Form */
        .login-right h4 { font-size: 1.6rem; font-weight: 800; color: #0f172a; text-align: center; margin-bottom: 8px;}
        .login-right p  { color: #64748b; font-size: .875rem; text-align: center; margin-bottom: 30px;}

        .form-control {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 12px 16px;
            font-size: .9rem;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; }

        .input-group .form-control { border-right: none; }
        .input-group .btn-outline-secondary {
            border: 1.5px solid #e2e8f0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #94a3b8;
        }

        .btn-login {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: all .2s;
        }
        .btn-login:hover { background: #1d4ed8; transform: translateY(-1px); }
        .alert { border: none; border-radius: 12px; font-size: .875rem; }

        @media (max-width: 767px) {
            .login-wrapper { max-width: 400px; }
            .login-right { padding: 36px 28px; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-right">
        <div>
            <h4>Sistem MBG</h4>
            <p>Masuk ke akun Anda untuk melanjutkan.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember" style="font-size:.85rem;">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const p = document.getElementById('password');
    const i = document.getElementById('toggle-icon');
    if (p.type === 'password') {
        p.type = 'text';
        i.className = 'bi bi-eye-slash';
    } else {
        p.type = 'password';
        i.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>