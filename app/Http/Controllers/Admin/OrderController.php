<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\DataTables\OrderDataTable;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders.
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.orders.index');
    }
    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.shade.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status and tracking ID.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pending,Packing,Shipped,Delivered,Cancelled',
            'tracking_id' => 'nullable|string|max:255',
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_id' => $request->tracking_id,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}