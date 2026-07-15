<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Default Users ──────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@mbg.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
        ]);

        $guru = User::create([
            'name'   => 'Bu Sari Guru',
            'email'  => 'guru@mbg.com',
            'password' => Hash::make('guru123'),
            'role'   => 'guru',
            'phone'  => '081234567891',
            'school' => 'Madrasah Aliyah Unggulan',
        ]);

        $asisten = User::create([
            'name'   => 'Dewi Asisten',
            'email'  => 'asisten@mbg.com',
            'password' => Hash::make('asisten123'),
            'role'   => 'asisten',
            'phone'  => '081234567892',
        ]);

        $driver = User::create([
            'name'   => 'Pak Joko Driver',
            'email'  => 'driver@mbg.com',
            'password' => Hash::make('driver123'),
            'role'   => 'driver',
            'phone'  => '081234567893',
        ]);

        // ── Sample Orders ──────────────────────────────────
        // Order 1: selesai
        $order1 = Order::create([
            'guru_id'            => $guru->id,
            'tanggal_pengiriman' => Carbon::today()->subDays(2),
            'jumlah_porsi_besar' => 15,
            'jumlah_porsi_kecil' => 5,
            'status'             => 'selesai',
            'catatan'            => 'Mohon tepat waktu ya',
        ]);
        Menu::create([
            'order_id'    => $order1->id,
            'lauk'        => 'Ayam Goreng Bumbu Kuning',
            'sayur'       => 'Tumis Kangkung',
            'buah'        => 'Pisang Ambon',
            'sambal'      => 'Sambal Terasi',
            'kalori'      => 520,
            'protein'     => 28,
            'lemak'       => 18,
            'karbohidrat' => 65,
        ]);
        $delivery1 = Delivery::create([
            'order_id'          => $order1->id,
            'driver_id'         => $driver->id,
            'status_pengiriman' => 'selesai',
            'tracking_active'   => false,
            'current_latitude'  => 0.400229,
            'current_longitude' => 101.856809,
            'delivered_at'      => Carbon::today()->subDays(2)->setTime(7, 30),
        ]);

        // Order 2: dalam perjalanan (aktif)
        $order2 = Order::create([
            'guru_id'            => $guru->id,
            'tanggal_pengiriman' => Carbon::today(),
            'jumlah_porsi_besar' => 20,
            'jumlah_porsi_kecil' => 8,
            'status'             => 'dalam_perjalanan',
            'catatan'            => 'Porsi anak kelas 1 yang kecil',
        ]);
        Menu::create([
            'order_id'    => $order2->id,
            'lauk'        => 'Ikan Bakar Bumbu Kecap',
            'sayur'       => 'Sayur Bayam Jagung',
            'buah'        => 'Jeruk Manis',
            'sambal'      => 'Sambal Bawang',
            'kalori'      => 480,
            'protein'     => 32,
            'lemak'       => 14,
            'karbohidrat' => 58,
        ]);
        $delivery2 = Delivery::create([
            'order_id'          => $order2->id,
            'driver_id'         => $driver->id,
            'status_pengiriman' => 'dalam_perjalanan',
            'tracking_active'   => true,
            'current_latitude'  => 0.400229,
            'current_longitude' => 101.856809,
        ]);

        // Order 3: pending (Sekarang dialihkan ke $guru)
        Order::create([
            'guru_id'            => $guru->id, 
            'tanggal_pengiriman' => Carbon::today()->addDays(3),
            'jumlah_porsi_besar' => 25,
            'jumlah_porsi_kecil' => 10,
            'status'             => 'pending',
        ]);

        // Order 4: siap dikirim
        $order4 = Order::create([
            'guru_id'            => $guru->id,
            'tanggal_pengiriman' => Carbon::tomorrow(),
            'jumlah_porsi_besar' => 18,
            'jumlah_porsi_kecil' => 6,
            'status'             => 'siap_dikirim',
        ]);
        Menu::create([
            'order_id'    => $order4->id,
            'lauk'        => 'Telur Dadar Bumbu Balado',
            'sayur'       => 'Capcay Sayuran',
            'buah'        => 'Semangka',
            'sambal'      => 'Sambal Ijo',
            'kalori'      => 450,
            'protein'     => 22,
            'lemak'       => 16,
            'karbohidrat' => 60,
        ]);
    }
}