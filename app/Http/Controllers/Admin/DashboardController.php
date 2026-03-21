<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Review;

// Charts
use App\Charts\SalesPerformanceChart;
use App\Charts\YearlyRevenueChart;
use App\Charts\ProductShareChart;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $pink = '#ec4899';

        // --- 1. Information Card Data ---
        // We JOIN order_items because the total_amount column is gone.
        $totalSales = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '=', 'Delivered')
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 0;

        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $averageRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // --- 2. Yearly Sales (Line Chart) ---
        // Summing (price * quantity) from order_items grouped by Year
        $yearlySalesData = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '=', 'Delivered')
            ->selectRaw('YEAR(orders.created_at) as year, SUM(order_items.price * order_items.quantity) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $yearlyChart = new YearlyRevenueChart;
        $yearlyChart->labels($yearlySalesData->pluck('year'));
        $yearlyChart->dataset('Yearly Revenue', 'line', $yearlySalesData->pluck('total'))
            ->color($pink)
            ->backgroundColor('rgba(236, 72, 153, 0.1)');

        // --- 3. Range Sales (Bar Chart) ---
        $barQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '=', 'Delivered')
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price * order_items.quantity) as total');
        
        if ($start && $end) {
            $barQuery->whereBetween('orders.created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        
        $rangeSales = $barQuery->groupBy('date')->orderBy('date')->get();

        $salesChart = new SalesPerformanceChart;
        $salesChart->labels($rangeSales->pluck('date'));
        $salesChart->dataset('Revenue', 'bar', $rangeSales->pluck('total'))
            ->backgroundColor($pink);

        // --- 4. Product Pie Chart (Revenue Share) ---
        // Note: I kept your join logic for shades -> products
        $productSales = DB::table('order_items')
            ->join('shades', 'order_items.shade_id', '=', 'shades.id')
            ->join('products', 'shades.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '=', 'Delivered')
            ->select('products.name', DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'))
            ->groupBy('products.name')
            ->orderByDesc('total_revenue')
            ->get();

        $pieColors = $productSales->map(function($item, $i) {
            return "hsl(330, 75%, " . max(25, 85 - ($i * 3.5)) . "%)";
        })->toArray();

        $pieChart = new ProductShareChart;
        $pieChart->labels($productSales->pluck('name'));
        $pieChart->dataset('Revenue Share', 'pie', $productSales->pluck('total_revenue'))
            ->backgroundColor($pieColors);

        // --- 5. Return View ---
        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,
            'totalBrands' => $totalBrands,
            'totalCategories' => $totalCategories,
            'averageRating' => number_format($averageRating, 1),
            'totalReviews' => $totalReviews,
            'salesChart' => $salesChart,
            'yearlyChart' => $yearlyChart,
            'pieChart' => $pieChart,
            'pieLabels' => $productSales->pluck('name'),
            'pieData' => $productSales->pluck('total_revenue'),
            'pieColors' => $pieColors,
            'start' => $start,
            'end' => $end,
        ]);
    }
}