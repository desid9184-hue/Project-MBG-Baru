<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'    => User::count(),
            'total_orders'   => Order::count(),
            'active_orders'  => Order::whereNotIn('status', ['selesai', 'dibatalkan'])->count(),
            'today_delivery' => Delivery::whereDate('created_at', today())->count(),
            'total_guru'     => User::where('role', 'guru')->count(),
            'total_driver'   => User::where('role', 'driver')->count(),
            'total_asisten'  => User::where('role', 'asisten')->count(),
        ];

        $recent_orders = Order::with('guru')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $active_deliveries = Delivery::with(['order.guru', 'driver'])
            ->whereIn('status_pengiriman', ['dalam_perjalanan', 'sampai_sekolah'])
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'active_deliveries'));
    }

    // ── Users ─────────────────────────────────────────────
    public function users(Request $request)
    {
        $query = User::query();
        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,guru,asisten,driver',
            'phone'    => 'nullable|string|max:20',
            'school'   => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'school'   => $request->school,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,guru,asisten,driver',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('name', 'email', 'role', 'phone', 'school'));

        if ($request->password) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // ── Orders ────────────────────────────────────────────
    public function orders(Request $request)
    {
        $query = Order::with('guru');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $orders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['guru', 'menu', 'delivery.driver', 'delivery.trackingLogs']);
        return view('admin.orders.show', compact('order'));
    }
}
