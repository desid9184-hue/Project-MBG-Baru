<?php

namespace App\Http\Controllers\Asisten;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AsisteController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'pesanan_baru'       => Order::where('status', 'pending')->count(),
            'sedang_diproses'    => Order::whereIn('status', ['diterima', 'diproses', 'dikemas'])->count(),
            'siap_dikirim'       => Order::where('status', 'siap_dikirim')->count(),
            'selesai_hari_ini'   => Order::where('status', 'selesai')
                                         ->whereDate('updated_at', today())
                                         ->count(),
        ];

        $pending_orders = Order::with('guru')
            ->where('status', 'pending')
            ->orderBy('tanggal_pengiriman')
            ->get();

        $processing_orders = Order::with(['guru', 'menu'])
            ->whereIn('status', ['diterima', 'diproses', 'dikemas'])
            ->orderBy('tanggal_pengiriman')
            ->get();

        return view('asisten.dashboard', compact('stats', 'pending_orders', 'processing_orders'));
    }

    public function orders(Request $request)
    {
        $query = Order::with(['guru', 'menu']);

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->whereNotIn('status', ['selesai', 'dibatalkan']);
        }

        $orders = $query->orderBy('tanggal_pengiriman')->paginate(15)->withQueryString();
        return view('asisten.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['guru', 'menu', 'delivery.driver']);
        return view('asisten.orders.show', compact('order'));
    }

    public function acceptOrder(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan sudah diproses sebelumnya.');
        }
        $order->update(['status' => 'diterima']);
        return back()->with('success', 'Pesanan diterima dan siap diproses.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $allowed = ['diproses', 'dikemas', 'siap_dikirim'];
        $request->validate([
            'status' => 'required|in:' . implode(',', $allowed),
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan diperbarui: ' . $order->fresh()->status_label);
    }

    public function inputMenu(Order $order)
    {
        if (!in_array($order->status, ['diterima', 'diproses', 'dikemas'])) {
            return back()->with('error', 'Pesanan belum diterima atau sudah selesai.');
        }
        $menu = $order->menu;
        return view('asisten.orders.input-menu', compact('order', 'menu'));
    }

    public function storeMenu(Request $request, Order $order)
    {
        $request->validate([
            'lauk'        => 'required|string|max:255',
            'sayur'       => 'required|string|max:255',
            'buah'        => 'required|string|max:255',
            'sambal'      => 'nullable|string|max:255',
            'kalori'      => 'required|numeric|min:0|max:9999',
            'protein'     => 'required|numeric|min:0|max:999',
            'lemak'       => 'required|numeric|min:0|max:999',
            'karbohidrat' => 'required|numeric|min:0|max:999',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        Menu::updateOrCreate(
            ['order_id' => $order->id],
            $request->only('lauk', 'sayur', 'buah', 'sambal', 'kalori', 'protein', 'lemak', 'karbohidrat', 'keterangan')
        );

        if ($order->status === 'diterima') {
            $order->update(['status' => 'diproses']);
        }

        return redirect()->route('asisten.orders.show', $order)->with('success', 'Menu dan kandungan gizi berhasil disimpan.');
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::findOrFail($request->driver_id);
        if ($driver->role !== 'driver') {
            return back()->with('error', 'User yang dipilih bukan driver.');
        }

        Delivery::updateOrCreate(
            ['order_id' => $order->id],
            [
                'driver_id'         => $request->driver_id,
                'status_pengiriman' => 'menunggu',
                'tracking_active'   => false,
            ]
        );

        return back()->with('success', 'Driver berhasil ditugaskan.');
    }
}
