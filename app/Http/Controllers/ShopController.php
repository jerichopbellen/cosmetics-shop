<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Shade;

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

    public function addToCart(Request $request)
    {
        // Validate that the shade exists and we have a quantity
        $request->validate([
            'shade_id' => 'required|exists:shades,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $shade = Shade::with('product')->findOrFail($request->shade_id);
        $cart = session()->get('cart', []);

        // Create a unique key for the cart (Product ID + Shade ID)
        $cartKey = $shade->product_id . '-' . $shade->id;

        if(isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                "product_name" => $shade->product->name,
                "shade_name" => $shade->shade_name,
                "quantity" => $request->quantity,
                "price" => $shade->price,
                "image" => $shade->image_path ?? $shade->product->images->first()->image_path,
                "shade_id" => $shade->id
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function viewCart()
    {
        return view('shop.cart');
    }
}
