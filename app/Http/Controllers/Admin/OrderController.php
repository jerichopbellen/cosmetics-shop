<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\DataTables\OrderDataTable;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

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

   
    public function dashboard(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // --- NEW: Data for Information Cards ---
        $totalSales = Order::where('status', 'Delivered')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        // 1. YEARLY SALES (Line Chart) - YOUR ORIGINAL LOGIC
        $yearlySales = Order::where('status', 'Delivered')
            ->selectRaw('YEAR(created_at) as year, SUM(total_amount) as total')
            ->groupBy('year')->orderBy('year', 'asc')->get();
        $lineLabels = $yearlySales->pluck('year');
        $lineData = $yearlySales->pluck('total');

        // 2. RANGE SALES (Bar Chart) - YOUR ORIGINAL LOGIC
        $barQuery = Order::where('status', 'Delivered')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total');
        if ($start && $end) {
            $barQuery->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        $rangeSales = $barQuery->groupBy('date')->orderBy('date')->get();
        $barLabels = $rangeSales->pluck('date');
        $barData = $rangeSales->pluck('total');

        // 3. PRODUCT PIE (ALL PRODUCTS) - YOUR ORIGINAL LOGIC
        $productSales = DB::table('order_items')
            ->join('shades', 'order_items.shade_id', '=', 'shades.id')
            ->join('products', 'shades.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Delivered')
            ->select('products.name', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('products.name')
            ->orderByDesc('total_revenue')
            ->get();

        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'lineLabels' => $lineLabels, 
            'lineData' => $lineData,
            'barLabels' => $barLabels, 
            'barData' => $barData,
            'pieLabels' => $productSales->pluck('name'),
            'pieData' => $productSales->pluck('total_revenue'),
            'start' => $start, 
            'end' => $end
        ]);
    }
}