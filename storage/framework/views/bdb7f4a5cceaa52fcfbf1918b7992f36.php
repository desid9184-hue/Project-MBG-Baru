<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistem MBG'); ?> - Monitoring Distribusi Makanan Bergizi</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:    #2563eb;
            --primary-dark: #1d4ed8;
            --success:    #10b981;
            --warning:    #f59e0b;
            --danger:     #ef4444;
            --info:       #06b6d4;
            --sidebar-w:  260px;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-active: #2563eb;
            --topbar-h:   64px;
            --font:       'Plus Jakarta Sans', sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font);
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ── Sidebar ────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1050;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid #1e293b;
            text-decoration: none;
        }

        .brand-icon {
            width: 42px; height: 42px;
            background: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: white;
            flex-shrink: 0;
        }

        .brand-text h6 {
            color: white; font-weight: 700; font-size: .9rem; margin: 0;
        }
        .brand-text small { color: var(--sidebar-text); font-size: .72rem; }

        .sidebar-nav { padding: 12px 0; }

        .nav-section {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #475569;
            padding: 12px 20px 4px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: var(--sidebar-text);
            font-size: .875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all .2s;
            text-decoration: none;
            position: relative;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: #1e293b;
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(37,99,235,.18);
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }

        .sidebar .nav-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* ── Topbar ─────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1040;
            gap: 16px;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
        }

        .topbar-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .user-avatar {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: .9rem;
            cursor: pointer;
        }

        /* ── Main ───────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-content { padding: 28px 28px 40px; }

        /* ── Cards ──────────────────────────────── */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            gap: 18px;
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
            color: inherit;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,.1);
            color: inherit;
        }

        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .stat-number { font-size: 1.75rem; font-weight: 800; line-height: 1; }
        .stat-label  { font-size: .8rem; color: #64748b; margin-top: 3px; font-weight: 500; }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h5 {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Table ──────────────────────────────── */
        .table-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .table thead th {
            background: #f8fafc;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        .table td { padding: 13px 16px; vertical-align: middle; font-size: .875rem; }
        .table tbody tr:hover { background: #f8fafc; }

        /* ── Badge ──────────────────────────────── */
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: .73rem;
            font-weight: 600;
        }

        /* ── Map ────────────────────────────────── */
        #map, #driver-map { height: 420px; border-radius: 0 0 16px 16px; }

        /* ── Buttons ────────────────────────────── */
        .btn { border-radius: 10px; font-weight: 600; font-size: .875rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-success  { background: var(--success); border-color: var(--success); }

        /* ── Alerts ─────────────────────────────── */
        .alert { border: none; border-radius: 12px; }

        /* ── Page Header ────────────────────────── */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .page-header p { color: #64748b; font-size: .875rem; margin: 0; }
        .breadcrumb { background: none; padding: 0; margin: 0; font-size: .8rem; }
        .breadcrumb-item.active { color: #64748b; }

        /* ── Form ───────────────────────────────── */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 10px 14px;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .form-label { font-weight: 600; font-size: .85rem; color: #374151; margin-bottom: 6px; }

        /* ── Tracking pulse ─────────────────────── */
        @keyframes pulse-dot {
            0%   { transform: scale(1); opacity: 1; }
            50%  { transform: scale(1.4); opacity: .5; }
            100% { transform: scale(1); opacity: 1; }
        }
        .pulse { animation: pulse-dot 1.5s infinite; }

        /* ── Responsive ─────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-toggle { display: block; }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
            .page-content { padding: 20px 16px; }
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            z-index: 1049;
        }
        .sidebar-overlay.show { display: block; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="#" class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-box-seam-fill"></i></div>
        <div class="brand-text">
            <h6>Sistem MBG</h6>
            <small>Makanan Bergizi</small>
        </div>
    </a>

    <nav class="sidebar-nav">
        <?php echo $__env->yieldContent('sidebar-menu'); ?>

        <div class="nav-section" style="margin-top:16px;">Akun</div>
        <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
            <?php echo csrf_field(); ?>
            <a href="#" class="nav-link text-danger" onclick="document.getElementById('logout-form').submit()">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </form>
    </nav>
</aside>

<!-- Topbar -->
<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <span class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></span>
    </div>
    <div class="topbar-right">
        <div class="text-end d-none d-md-block">
            <div style="font-size:.85rem;font-weight:700;color:#1e293b;"><?php echo e(auth()->user()->name); ?></div>
            <div style="font-size:.75rem;color:#64748b;"><?php echo e(auth()->user()->role_label); ?></div>
        </div>
        <div class="dropdown">
            <div class="user-avatar" data-bs-toggle="dropdown">
                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius:12px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <li><span class="dropdown-item-text fw-bold py-2"><?php echo e(auth()->user()->name); ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="bi bi-box-arrow-left me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Main -->
<main class="main-content">
    <div class="page-content">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.getElementById('sidebarOverlay').classList.remove('show');
}
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\mbg-system\resources\views\layouts\app.blade.php ENDPATH**/ ?>