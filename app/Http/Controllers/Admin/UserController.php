<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\DataTables\UserDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function show(User $user)
    {
        $user->load(['orders.orderItems.shade.product']);
        return view('admin.users.show', compact('user'));
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