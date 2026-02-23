<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the product catalog.
     */
    public function index(Request $request)
    {
        // Eager load relations to prevent N+1 query issues
        $query = Product::with(['brand', 'category', 'shades', 'images']);

        // Optional: Filter by category if passed in URL
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);

        return view('shop.index', compact('products'));
    }

    /**
     * Display a specific product with dynamic shade selection.
     */
    public function show(Product $product)
    {
        // Load relations for the specific product
        $product->load(['brand', 'category', 'shades', 'images']);
        
        // Get related products (same category) for the "You May Also Like" section
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
