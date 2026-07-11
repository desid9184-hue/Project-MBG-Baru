<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    /**
     * GET /api/deliveries
     * Header: Authorization: Bearer {token}
     * Query (opsional): ?tanggal=2026-07-08  (default: hari ini)
     *
     * Mengembalikan daftar tugas pengiriman milik driver yang sedang
     * login untuk tanggal tertentu (default hari ini), lengkap dengan
     * data sekolah tujuan (untuk ditampilkan di list "tugas hari ini").
     */
    public function index(Request $request)
    {
        $driver = $request->user();

        // HAPUS ATAU KOMENTAR BAGIAN whereHas TANGGAL SEMENTARA
        $deliveries = Delivery::with(['order.user.school', 'order.menu'])
            ->where('driver_id', $driver->id)
            // ->whereHas('order', function ($q) use ($tanggal) { ... }) 
            ->orderBy('created_at')
            ->get()
            ->map(fn($delivery) => $this->formatDelivery($delivery));

        return response()->json([
            'data' => $deliveries,
        ]);
    }

    /**
     * GET /api/deliveries/{id}
     * Header: Authorization: Bearer {token}
     *
     * Detail satu delivery, termasuk koordinat sekolah tujuan
     * (dipakai untuk gambar rute di peta OpenStreetMap).
     */
    public function show(Request $request, $id)
    {
        $driver = $request->user();

        $delivery = Delivery::with(['order.guru.school', 'order.menu'])
            ->where('driver_id', $driver->id)
            ->findOrFail($id);

        return response()->json($this->formatDelivery($delivery, detail: true));
    }

    /**
     * POST /api/deliveries/{id}/status
     * Header: Authorization: Bearer {token}
     * Body: status (string)
     *
     * Update status pengiriman. Sesuaikan daftar $statusValid di bawah
     * dengan pilihan enum "status_pengiriman" yang sebenarnya ada di
     * migration tabel delivery Anda.
     *
     * Saat status diubah ke status "berangkat" (dalam_perjalanan),
     * tracking_active otomatis diaktifkan. Saat status "selesai",
     * tracking_active otomatis dimatikan dan delivered_at diisi.
     */
    public function updateStatus(Request $request, $id)
    {
        $driver = $request->user();

        $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);

        $statusValid = ['menunggu', 'dalam_perjalanan', 'sampai_sekolah', 'selesai'];

        $request->validate([
            'status'         => ['required', Rule::in($statusValid)],
            'catatan_driver' => ['nullable', 'string'],
        ]);

        $delivery->status_pengiriman = $request->status;

        if ($request->filled('catatan_driver')) {
            $delivery->catatan_driver = $request->catatan_driver;
        }

        // Aktifkan tracking otomatis saat driver mulai berangkat
        if ($request->status === 'dalam_perjalanan') {
            $delivery->tracking_active = true;
        }

        // Matikan tracking & catat waktu selesai saat delivery tuntas
        if ($request->status === 'selesai') {
            $delivery->tracking_active = false;
            $delivery->delivered_at    = now();
        }

        $delivery->save();

        return response()->json([
            'message' => 'Status pengiriman berhasil diupdate',
            'data'    => $this->formatDelivery($delivery->fresh(['order.guru.school'])),
        ]);
    }

    /**
     * POST /api/deliveries/{id}/location
     * Header: Authorization: Bearer {token}
     * Body: latitude (decimal), longitude (decimal)
     *
     * Dipanggil oleh aplikasi Android setiap 10 detik selama
     * tracking_active bernilai true, untuk update posisi driver
     * saat ini secara real-time.
     */
    public function updateLocation(Request $request, $id)
    {
        $driver = $request->user();

        $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Kalau delivery belum aktif tracking-nya, tolak update lokasi
        // supaya tidak ada data lokasi "nyasar" masuk untuk delivery
        // yang belum/sudah selesai.
        if (! $delivery->tracking_active) {
            return response()->json([
                'message' => 'Tracking untuk delivery ini sedang tidak aktif.',
            ], 422);
        }

        $delivery->update([
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
        ]);

        return response()->json([
            'message'   => 'Lokasi berhasil diupdate',
            'latitude'  => $delivery->current_latitude,
            'longitude' => $delivery->current_longitude,
            'updated_at' => $delivery->updated_at,
        ]);
    }

    /**
     * Helper: format response delivery supaya konsisten & sudah termasuk
     * koordinat sekolah tujuan untuk digambar rutenya di peta.
     */
    private function formatDelivery(Delivery $delivery, bool $detail = false): array
    {
        $order  = $delivery->order;
        $guru   = $order?->user;
        $school = $guru?->school;

        $schoolData = is_object($school) ? $school : new \App\Models\School();

        $data = [
            'delivery_id'       => $delivery->id,
            'status_pengiriman' => $delivery->status_pengiriman,
            'sekolah_tujuan'    => [
                'nama'      => $schoolData->nama_sekolah ?? 'N/A',
                'alamat'    => $schoolData->alamat ?? 'N/A',
                'latitude'  => $schoolData->latitude ?? 0,
                'longitude' => $schoolData->longitude ?? 0,
            ],
        ];

        if ($detail) {
            $data['catatan_order']  = $order?->catatan;
            $data['catatan_driver'] = $delivery->catatan_driver;
            $data['menu']           = $order?->menu;
        }

        return $data;
    }
}
