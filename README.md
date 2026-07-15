# 🍱 Sistem Monitoring Distribusi MBG (Makanan Bergizi)
### Live Tracking Pengiriman Makanan Bergizi ke Sekolah

---

## 📋 Deskripsi Proyek

Sistem ini adalah aplikasi web berbasis **Laravel 12** untuk memonitor distribusi Makanan Bergizi (MBG) ke sekolah secara realtime. Dibangun sebagai tugas **Project Based Learning (PjBL)**.

### Fitur Utama
- ✅ **RBAC** — 4 role: Admin, Guru, Asisten Lapangan, Driver  
- ✅ **Live GPS Tracking** dengan Leaflet.js + OpenStreetMap (polling 5 detik)
- ✅ **Pemesanan H-3** — Guru input jumlah ompreng 3 hari sebelumnya
- ✅ **Input Gizi** — Asisten input kalori, protein, lemak, karbohidrat
- ✅ **Rute Perjalanan** — Visualisasi polyline rute driver di peta
- ✅ **Dashboard Modern** — Bootstrap 5, sidebar, responsive

---

## 🖥️ Teknologi

| Komponen   | Versi         |
|------------|---------------|
| PHP        | 8.2+          |
| Laravel    | 12.x          |
| MySQL      | 8.0+          |
| Bootstrap  | 5.3           |
| Leaflet.js | 1.9.4         |
| Spatie Permission | 6.x  |

---

## ⚙️ Instalasi di Laragon + VS Code

### Prasyarat
- **Laragon Full** (Apache, MySQL 8, PHP 8.2+) — https://laragon.org
- **Composer** — https://getcomposer.org
- **Node.js** (opsional, untuk asset build)
- **VS Code** + ekstensi PHP Intelephense

---

### Langkah 1: Clone / Ekstrak Project

Letakkan folder project di:
```
C:\laragon\www\mbg-sistem\
```

### Langkah 2: Install Dependencies

Buka terminal di folder project:
```bash
composer install
```

### Langkah 3: Setup Environment

```bash
# Salin file .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit file `.env`:
```env
APP_NAME="Sistem MBG"
APP_URL=http://mbg-sistem.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbg_sistem
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Buat Database

**Opsi A — Via phpMyAdmin Laragon:**
1. Buka `http://localhost/phpmyadmin`
2. Buat database baru: `mbg_sistem`
3. Klik tab **Import** → pilih file `mbg_sistem.sql`
4. Klik **Go** — database siap!

**Opsi B — Via Artisan (migration + seeder):**
```bash
php artisan migrate --seed
```

### Langkah 5: Jalankan Server

```bash
php artisan serve
```

Atau akses langsung via Laragon: `http://mbg-sistem.test`

---

## 👥 Akun Default

| Role    | Email              | Password   |
|---------|--------------------|------------|
| Admin   | admin@mbg.com      | password   |
| Guru    | guru@mbg.com       | password   |
| Asisten | asisten@mbg.com    | password   |
| Driver  | driver@mbg.com     | password   |

---

## 🗂️ Struktur Folder

```
mbg-sistem/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/AdminController.php
│   │   │   ├── Guru/GuruController.php
│   │   │   ├── Asisten/AsisteController.php
│   │   │   ├── Driver/DriverController.php
│   │   │   └── Auth/AuthController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Order.php
│       ├── Menu.php
│       ├── Delivery.php
│       └── TrackingLog.php
├── bootstrap/
│   └── app.php               ← Middleware registration
├── database/
│   ├── migrations/           ← 6 migration files
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php ← Master layout + sidebar
│   ├── auth/login.blade.php
│   ├── admin/
│   ├── guru/
│   ├── asisten/
│   └── driver/
├── routes/web.php
├── mbg_sistem.sql            ← Import ke phpMyAdmin
└── .env.example
```

---

## 🔄 Alur Sistem

```
Guru (H-3)         →  Buat Pesanan (jumlah ompreng besar/kecil)
Asisten Lapangan   →  Terima & proses pesanan
Asisten Lapangan   →  Input menu + kandungan gizi
Asisten Lapangan   →  Ubah status: Diproses → Dikemas → Siap Dikirim
Asisten Lapangan   →  Tugaskan driver
Driver             →  Aktifkan GPS tracking
Driver             →  Update lokasi realtime (watchPosition)
Guru               →  Pantau live tracking di peta (auto-refresh 5 detik)
Driver             →  Konfirmasi sampai sekolah & selesai
Guru               →  Lihat status selesai
```

---

## 🗺️ Live Tracking — Cara Kerja

**Driver Side (GPS Collection):**
1. Driver klik "Mulai Tracking GPS" → `navigator.geolocation.watchPosition()`
2. Setiap update koordinat → POST ke `/driver/deliveries/{id}/update-location`
3. Server simpan ke tabel `tracking_logs` + update `deliveries.current_latitude/longitude`

**Guru Side (Map Viewer):**
1. Halaman tracking load Leaflet.js + OpenStreetMap
2. Setiap **5 detik** — AJAX fetch ke `/guru/tracking/{order}/data`
3. Response JSON: koordinat terkini + array semua log
4. Driver marker bergerak, polyline rute digambar ulang

---

## 🚀 API Endpoints Tracking

| Method | Endpoint                                      | Deskripsi                    |
|--------|-----------------------------------------------|------------------------------|
| POST   | `/driver/deliveries/{id}/start-tracking`      | Mulai tracking + set aktif   |
| POST   | `/driver/deliveries/{id}/update-location`     | Update koordinat GPS         |
| POST   | `/driver/deliveries/{id}/arrived`             | Konfirmasi sampai sekolah    |
| POST   | `/driver/deliveries/{id}/stop-tracking`       | Hentikan tracking            |
| GET    | `/guru/tracking/{order}/data`                 | Ambil data tracking (AJAX)   |

---

## ❗ Troubleshooting

**Error: `Class "Spatie\Permission\..." not found`**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

**Error: `SQLSTATE[42S02]: Table ... doesn't exist`**
```bash
php artisan migrate:fresh --seed
```

**GPS tidak bekerja di browser:**
- GPS hanya bekerja di **HTTPS** atau **localhost**
- Di Laragon gunakan `http://localhost` bukan custom domain untuk test GPS

**Halaman 403 Forbidden:**
- Pastikan user sudah login
- Pastikan role user sesuai (cek kolom `role` di tabel `users`)

---

## 📚 Referensi PjBL

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Spatie Permission](https://spatie.be/docs/laravel-permission)
- [Leaflet.js](https://leafletjs.com)
- [OpenStreetMap](https://www.openstreetmap.org)
- [Bootstrap 5](https://getbootstrap.com)
- [MDN Geolocation API](https://developer.mozilla.org/en-US/docs/Web/API/Geolocation_API)

---

*Dibuat untuk tugas Project Based Learning — Sistem MBG 2025*
