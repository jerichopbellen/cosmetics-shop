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
use App\Models\Category;
use App\Models\Brand;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Exception;
use Illuminate\Support\Facades\Log;


class ShopController extends Controller
{
    /**
     * Display the product catalog.
     */

    public function index(Request $request)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $searchTerm = $request->input('search');

        // Use Scout's search, but handle filters inside the query callback
        $products = Product::search($searchTerm)
            ->query(function ($query) use ($request) {
                $query->with(['brand', 'category', 'shades', 'images']);

                // Filter by Category name
                if ($request->filled('category')) {
                    $query->whereHas('category', function($q) use ($request) {
                        $q->where('name', $request->category);
                    });
                }

                // Filter by Brand name
                if ($request->filled('brand')) {
                    $query->whereHas('brand', function($q) use ($request) {
                        $q->where('name', $request->brand);
                    });
                }

                // Filter by Price (from the shades relationship)
                if ($request->filled('min_price')) {
                    $query->whereHas('shades', function($q) use ($request) {
                        $q->where('price', '>=', $request->min_price);
                    });
                }

                if ($request->filled('max_price')) {
                    $query->whereHas('shades', function($q) use ($request) {
                        $q->where('price', '<=', $request->max_price);
                    });
                }
            })
            ->simplePaginate(12);

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        // Load relations including the nested review details
        $product->load([
            'brand', 
            'category', 
            'shades', 
            'images',
            'reviews' => function($query) {
                $query->latest();
            },
            'reviews.user', 
            'reviews.shade'  
        ]);
        
        return view('shop.show', compact('product'));
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
        // 1. Validate the shipping data
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
            // 2. Create the Order WITHOUT 'total_amount'
            // Since the column is dropped, we let the Accessor handle the math later.
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'GLOW-' . strtoupper(Str::random(10)),
                // REMOVED 'total_amount' line from here
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
                    'price'    => $item['price'], // Snapshotting price here is crucial for 3NF!
                ]);

                $shade->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            $order->load('user', 'orderItems.shade.product');

            try {
                Mail::to($request->user()->email)->send(new OrderPlaced($order));
            } catch (\Exception $e) {
                Log::error("Mail failed: " . $e->getMessage());
            }

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

    public function myOrders()
    {
        // Check if user is logged in to avoid errors
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with([
                'orderItems.shade.product.images', 
                'orderItems.shade.product.brand'
            ])
            ->latest()
            ->get();

        $currentOrders = $orders->whereIn('status', ['Pending', 'Packing', 'Shipped']);
        $orderHistory = $orders->whereIn('status', ['Delivered', 'Cancelled']);

        return view('shop.my-orders', compact('currentOrders', 'orderHistory'));
    }

    public function cancel(Order $order)
    {
        if (!\in_array($order->status, ['Pending', 'Packing'])) {
            return back()->with('error', 'Too late! This order is already being shipped.');
        }

        DB::beginTransaction();

        try {
            // Restore stock for all items in the order
            foreach ($order->orderItems as $item) {
                $item->shade->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'Cancelled']);
            DB::commit();

            return back()->with('success', "Order #{$order->order_number} cancelled successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

}
