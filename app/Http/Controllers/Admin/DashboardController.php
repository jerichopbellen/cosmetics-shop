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
use App\Models\Review; // Added Review Model

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
        $totalSales = Order::where('status', '=', 'Delivered')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $averageRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // --- 2. Yearly Sales (Line Chart) ---
        $yearlySalesData = Order::where('status', '=', 'Delivered')
            ->selectRaw('YEAR(created_at) as year, SUM(total_amount) as total')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $yearlyChart = new YearlyRevenueChart;
        $yearlyChart->labels($yearlySalesData->pluck('year'));
        $yearlyChart->dataset('Yearly Revenue', 'line', $yearlySalesData->pluck('total'))
            ->color($pink)
            ->backgroundColor('rgba(236, 72, 153, 0.1)');

        // --- 3. Range Sales (Bar Chart) ---
        $barQuery = Order::where('status', '=', 'Delivered')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total');
        
        if ($start && $end) {
            $barQuery->whereBetween('created_at', ["{$start} 00:00:00", "{$end} 23:59:59"]);
        }
        
        $rangeSales = $barQuery->groupBy('date')->orderBy('date')->get();

        $salesChart = new SalesPerformanceChart;
        $salesChart->labels($rangeSales->pluck('date'));
        $salesChart->dataset('Revenue', 'bar', $rangeSales->pluck('total'))
            ->backgroundColor($pink);

        // --- 4. Product Pie Chart (Revenue Share) ---
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
            'averageRating' => number_format($averageRating, 1), // Format to 1 decimal place
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