<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Shade;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $products = $query->latest()->simplePaginate(12);

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

    public function removeFromCart($key)
    {
        $cart = session()->get('cart');

        if(isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        
        if (!$cart || count($cart) == 0) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        return view('shop.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        // 1. Validate the shipping data from your new blade
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'city' => 'required|string',
            'payment_method' => 'required'
        ]);

        $cart = session()->get('cart');
        if (!$cart) return redirect()->route('shop.index');

        DB::beginTransaction();

        try {
            // 2. Create the Order with shipping details
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'GLOW-' . strtoupper(Str::random(10)),
                'total_amount' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
                'status' => 'Pending',
                'address' => $request->address,
                'phone' => $request->phone,
                'city' => $request->city,
                'payment_method' => $request->payment_method,
            ]);

            // 3. Create Items & Deduct Stock
            foreach ($cart as $item) {
                $shade = Shade::where('id', $item['shade_id'])->lockForUpdate()->first();

                if (!$shade || $shade->stock < $item['quantity']) {
                    throw new \Exception("Item " . $item['product_name'] . " is no longer available.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'shade_id' => $item['shade_id'],
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                ]);

                $shade->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            // Redirect to a success page (we will build this next)
            return redirect()->route('checkout.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function success($order_number)
    {
        return view('shop.success', compact('order_number'));
    }

    public function update(Request $request, Order $order)
    {
        // 1. Validate only the status
        $validated = $request->validate([
            'status' => 'required|in:Pending,Packing,Shipped,Delivered,Cancelled',
        ]);

        try {
            // 2. Update only the status field
            $order->update([
                'status' => $validated['status'],
            ]);

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', "Order status updated to {$validated['status']}.");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to update order status.');
        }
    }
}
