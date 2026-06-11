<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuruController extends Controller
{
    public function dashboard()
    {
        $guru = auth()->user();

        $stats = [
            'total_pesanan'    => Order::where('guru_id', $guru->id)->count(),
            'pesanan_aktif'    => Order::where('guru_id', $guru->id)
                                       ->whereNotIn('status', ['selesai', 'dibatalkan'])
                                       ->count(),
            'pengiriman_hari_ini' => Order::where('guru_id', $guru->id)
                                          ->whereDate('tanggal_pengiriman', today())
                                          ->count(),
            'pesanan_selesai'  => Order::where('guru_id', $guru->id)
                                       ->where('status', 'selesai')
                                       ->count(),
        ];

        $recent_orders = Order::with(['menu', 'delivery.driver'])
            ->where('guru_id', $guru->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $active_delivery = Delivery::with(['order', 'driver'])
            ->whereHas('order', fn($q) => $q->where('guru_id', $guru->id))
            ->where('tracking_active', true)
            ->first();

        return view('guru.dashboard', compact('stats', 'recent_orders', 'active_delivery'));
    }

    public function createOrder()
    {
        $min_date = Carbon::now()->addDays(3)->format('Y-m-d');
        return view('guru.orders.create', compact('min_date'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'tanggal_pengiriman'  => 'required|date|after:' . Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_porsi_besar'  => 'required|integer|min:0',
            'jumlah_porsi_kecil'  => 'required|integer|min:0',
            'catatan'             => 'nullable|string|max:500',
        ], [
            'tanggal_pengiriman.after' => 'Pesanan harus dibuat minimal H-3 sebelum tanggal pengiriman.',
        ]);

        if ($request->jumlah_porsi_besar + $request->jumlah_porsi_kecil === 0) {
            return back()->withErrors(['jumlah_porsi_besar' => 'Total porsi minimal 1.'])->withInput();
        }

        Order::create([
            'guru_id'            => auth()->id(),
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
            'jumlah_porsi_besar' => $request->jumlah_porsi_besar,
            'jumlah_porsi_kecil' => $request->jumlah_porsi_kecil,
            'status'             => 'pending',
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('guru.orders')->with('success', 'Pesanan berhasil dibuat! Asisten lapangan akan segera memproses.');
    }

    public function orders(Request $request)
    {
        $query = Order::with(['menu', 'delivery.driver'])
            ->where('guru_id', auth()->id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderByDesc('tanggal_pengiriman')->paginate(10)->withQueryString();
        return view('guru.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['menu', 'delivery.driver', 'delivery.trackingLogs' => function($q) {
            $q->orderBy('recorded_at');
        }]);
        return view('guru.orders.show', compact('order'));
    }

    public function tracking(Order $order)
    {
        $this->authorizeOrder($order);
        $delivery = $order->delivery;

        return view('guru.tracking', compact('order', 'delivery'));
    }

    // AJAX: get live tracking data
    public function getTrackingData(Order $order)
    {
        $this->authorizeOrder($order);
        $delivery = $order->delivery;

        if (!$delivery) {
            return response()->json(['error' => 'Pengiriman belum dimulai'], 404);
        }

        $logs = TrackingLog::where('delivery_id', $delivery->id)
            ->orderBy('recorded_at')
            ->get(['latitude', 'longitude', 'recorded_at']);

        return response()->json([
            'delivery_id'       => $delivery->id,
            'status'            => $delivery->status_pengiriman,
            'status_label'      => $delivery->status_label,
            'status_color'      => $delivery->status_color,
            'tracking_active'   => $delivery->tracking_active,
            'current_latitude'  => $delivery->current_latitude,
            'current_longitude' => $delivery->current_longitude,
            'driver_name'       => $delivery->driver->name ?? 'N/A',
            'logs'              => $logs,
            'last_update'       => $delivery->updated_at?->diffForHumans(),
        ]);
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }
    }
}
