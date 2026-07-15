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
     *
     * Mengembalikan daftar tugas pengiriman milik driver yang sedang
     * login, lengkap dengan data guru, sekolah tujuan, dan porsi.
     * Struktur response ini disesuaikan persis dengan model Java
     * (DeliveryListResponse & Delivery) di aplikasi Android.
     */
    public function index(Request $request)
    {
        $driver  = $request->user();
        $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

        $deliveries = Delivery::with(['order.guru.schoolData', 'order.menu'])
            ->where('driver_id', $driver->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn($delivery) => $this->formatDelivery($delivery));

        return response()->json([
            'tanggal' => $tanggal,
            'total'   => $deliveries->count(),
            'data'    => $deliveries,
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

        $delivery = Delivery::with(['order.guru.schoolData', 'order.menu'])
            ->where('driver_id', $driver->id)
            ->findOrFail($id);

        return response()->json($this->formatDelivery($delivery, detail: true));
    }

    /**
     * POST /api/deliveries/{id}/status
     * Header: Authorization: Bearer {token}
     * Body: status (string)
     *
     * Update status pengiriman. Saat status diubah ke "dalam_perjalanan",
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

        if ($request->status === 'dalam_perjalanan') {
            $delivery->tracking_active = true;
        }

        if ($request->status === 'selesai') {
            $delivery->tracking_active = false;
            $delivery->delivered_at    = now();
        }

        $delivery->save();

        if (in_array($request->status, ['dalam_perjalanan', 'sampai_sekolah', 'selesai'])) {
    $delivery->order()->update(['status' => $request->status]);
}

        return response()->json([
            'message' => 'Status pengiriman berhasil diupdate',
            'data'    => $this->formatDelivery($delivery->fresh(['order.guru.schoolData'])),
        ]);
    }

    /**
     * POST /api/deliveries/{id}/location
     * Header: Authorization: Bearer {token}
     * Body: latitude (decimal), longitude (decimal)
     *
     * Dipanggil oleh aplikasi Android setiap 10 detik selama
     * tracking_active bernilai true.
     */
    public function updateLocation(Request $request, $id)
    {
        $driver = $request->user();

        $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

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
            'message'    => 'Lokasi berhasil diupdate',
            'latitude'   => $delivery->current_latitude,
            'longitude'  => $delivery->current_longitude,
            'updated_at' => $delivery->updated_at,
        ]);
    }

    /**
     * Helper: format response delivery. Struktur field DI SINI disesuaikan
     * PERSIS dengan model Java Delivery.java di aplikasi Android:
     * delivery_id, status_pengiriman, tracking_active, current_latitude,
     * current_longitude, delivered_at, tanggal_pengiriman,
     * jumlah_porsi_besar, jumlah_porsi_kecil, guru{nama,phone},
     * sekolah_tujuan{nama,alamat,latitude,longitude}
     */
    private function formatDelivery(Delivery $delivery, bool $detail = false): array
    {
        $order  = $delivery->order;
        $guru   = $order?->guru;
        $school = $guru?->schoolData;

        $data = [
            'delivery_id'        => $delivery->id,
            'status_pengiriman'  => $delivery->status_pengiriman,
            'tracking_active'    => (bool) $delivery->tracking_active,
            'current_latitude'   => $delivery->current_latitude,
            'current_longitude'  => $delivery->current_longitude,
            'delivered_at'       => $delivery->delivered_at,
            'tanggal_pengiriman' => $order?->tanggal_pengiriman,
            'jumlah_porsi_besar' => $order?->jumlah_porsi_besar ?? 0,
            'jumlah_porsi_kecil' => $order?->jumlah_porsi_kecil ?? 0,
            'guru'               => [
                'nama'  => $guru?->name ?? 'N/A',
                'phone' => $guru?->phone ?? '-',
            ],
            'sekolah_tujuan'     => [
                'nama'      => $school?->nama_sekolah ?? 'N/A',
                'alamat'    => $school?->alamat ?? 'N/A',
                'latitude'  => $school?->latitude ?? 0,
                'longitude' => $school?->longitude ?? 0,
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
