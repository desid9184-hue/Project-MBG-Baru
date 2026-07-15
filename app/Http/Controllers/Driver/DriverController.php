<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driver = auth()->user();

        $stats = [
            'pengiriman_aktif'   => Delivery::where('driver_id', $driver->id)
                                            ->whereIn('status_pengiriman', ['menunggu', 'dalam_perjalanan', 'sampai_sekolah'])
                                            ->count(),
            'pengiriman_selesai' => Delivery::where('driver_id', $driver->id)
                                            ->where('status_pengiriman', 'selesai')
                                            ->count(),
            'hari_ini'           => Delivery::where('driver_id', $driver->id)
                                            ->whereDate('created_at', today())
                                            ->count(),
        ];

        $active_deliveries = Delivery::with(['order.guru', 'order.menu'])
            ->where('driver_id', $driver->id)
            ->whereIn('status_pengiriman', ['menunggu', 'dalam_perjalanan', 'sampai_sekolah'])
            ->orderBy('created_at')
            ->get();

        $completed_today = Delivery::with(['order.guru'])
            ->where('driver_id', $driver->id)
            ->where('status_pengiriman', 'selesai')
            ->whereDate('delivered_at', today())
            ->get();

        return view('driver.dashboard', compact('stats', 'active_deliveries', 'completed_today'));
    }

    public function deliveries(Request $request)
    {
        $query = Delivery::with(['order.guru', 'order.menu'])
            ->where('driver_id', auth()->id());

        if ($request->status) {
            $query->where('status_pengiriman', $request->status);
        }

        $deliveries = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('driver.deliveries.index', compact('deliveries'));
    }

    public function showDelivery(Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);
        $delivery->load(['order.guru', 'order.menu', 'trackingLogs' => function($q) {
            $q->orderBy('recorded_at');
        }]);
        return view('driver.deliveries.show', compact('delivery'));
    }

    public function startTracking(Request $request, Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $delivery->update([
            'status_pengiriman' => 'dalam_perjalanan',
            'tracking_active'   => true,
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
        ]);

        // Update order status
        $delivery->order->update(['status' => 'dalam_perjalanan']);

        // Log starting point
        TrackingLog::create([
            'delivery_id' => $delivery->id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'accuracy'    => $request->accuracy ?? null,
            'recorded_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tracking dimulai. Selamat bertugas!',
        ]);
    }

    public function updateLocation(Request $request, Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);

        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'speed'     => 'nullable|numeric|min:0',
            'accuracy'  => 'nullable|numeric|min:0',
        ]);

        $delivery->update([
            'current_latitude'  => $request->latitude,
            'current_longitude' => $request->longitude,
            'tracking_active'   => true,
        ]);

        TrackingLog::create([
            'delivery_id' => $delivery->id,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'speed'       => $request->speed,
            'accuracy'    => $request->accuracy,
            'recorded_at' => Carbon::now(),
        ]);

        return response()->json([
            'success'   => true,
            'timestamp' => Carbon::now()->toISOString(),
        ]);
    }

    public function arrivedAtSchool(Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);

        $delivery->update([
            'status_pengiriman' => 'sampai_sekolah',
        ]);
        $delivery->order->update(['status' => 'sampai_sekolah']);

        return response()->json([
            'success' => true,
            'message' => 'Status diperbarui: Sampai di Sekolah',
        ]);
    }

    public function completeDelivery(Request $request, Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);

        $delivery->update([
            'status_pengiriman' => 'selesai',
            'tracking_active'   => false,
            'delivered_at'      => Carbon::now(),
            'catatan_driver'    => $request->catatan,
        ]);
        $delivery->order->update(['status' => 'selesai']);

        return redirect()->route('driver.dashboard')->with('success', 'Pengiriman selesai dikonfirmasi. Terima kasih!');
    }

    public function stopTracking(Delivery $delivery)
    {
        $this->authorizeDelivery($delivery);
        $delivery->update(['tracking_active' => false]);

        return response()->json(['success' => true, 'message' => 'Tracking dihentikan.']);
    }

    private function authorizeDelivery(Delivery $delivery): void
    {
        if ($delivery->driver_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengiriman ini.');
        }
    }
}
