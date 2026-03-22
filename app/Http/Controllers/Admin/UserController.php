<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\DataTables\UserDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function show(User $user)
    {
        $user->load(['orders.orderItems.shade.product']);

        $userTotal = $user->orders()->where('status', 'Delivered')
            ->with('orderItems')
            ->get()
            ->sum(fn($order) => $order->orderItems->sum(fn($item) => $item->price * $item->quantity));

        $allTotals = User::withSum(['orderItems as total_spent' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'Delivered');
                })->select(DB::raw('SUM(price * quantity)'));
            }], 'price')
            ->pluck('total_spent')
            ->map(fn($val) => $val ?? 0)
            ->sortDesc()
            ->values();

        $totalUsers = $allTotals->count();
        
        if ($userTotal > 0 && $totalUsers > 0) {
            $rank = $allTotals->search(fn($val) => $val <= $userTotal) + 1;
            $percentile = ($rank / $totalUsers) * 100;
            
            $userRank = 'Top ' . ceil($percentile) . '%';
        } else {
            $userRank = 'No Rank';
        }

        return view('admin.users.show', compact('user', 'userRank', 'userTotal'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent admins from changing their own role to stay in the system
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot change your own administrative role.');
        }

        // Validate the role input
        $request->validate([
            'role' => 'required|in:customer,admin',
        ]);

        // Update only the role column
        $user->update([
            'role' => $request->role
        ]);

        $roleName = strtoupper($request->role);

        return redirect()->back()->with('success', "Role for {$user->name} has been updated to {$roleName}.");
    }

    public function updateStatus(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $request->validate([
            'is_active' => 'required|in:0,1', 
        ]);

        $user->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "User '{$user->name}' has been successfully {$status}.");
    }
}