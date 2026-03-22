<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\DataTables\OrderDataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Exception;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderStatusUpdated;

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

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            if ($newStatus === 'Cancelled' && $oldStatus !== 'Cancelled') {
                foreach ($order->orderItems as $item) {
                    $item->shade()->increment('stock', $item->quantity);
                }
            }
            
            if ($oldStatus === 'Cancelled' && $newStatus !== 'Cancelled') {
                foreach ($order->orderItems as $item) {
                    if ($item->shade->stock < $item->quantity) {
                        throw new \Exception("Cannot restore order. Insufficient stock for " . $item->shade->shade_name);
                    }
                    $item->shade()->decrement('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => $newStatus,
                'tracking_id' => $request->tracking_id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        } catch (\Exception $e) {
            Log::error("Status Update Email Failed: " . $e->getMessage());
        } 

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

}