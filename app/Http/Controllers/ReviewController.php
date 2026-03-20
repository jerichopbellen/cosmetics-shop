<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Shade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        // Use findOrFail to ensure the product and shade actually exist
        $product = Product::findOrFail($request->product);
        $shade = Shade::findOrFail($request->shade);

        return view('reviews.create', compact('product', 'shade'));
    }

    /**
     * Store a new review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'shade_id'   => 'required|exists:shades,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Match the Blade 'name="image"'
        ]);

        $exists = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('shade_id', $request->shade_id)
            ->exists();

        if ($exists) {
            return redirect()->route('orders.my')->with('error', 'You have already reviewed this shade.');
        }

        $review = new Review();
        $review->user_id = Auth::id();
        $review->product_id = $request->product_id;
        $review->shade_id = $request->shade_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;

        // Check for 'image' because that is what the <input> is named
        if ($request->hasFile('image')) {
            $review->photo_path = $request->file('image')->store('reviews', 'public');
        }

        $review->save();

        return redirect()->route('orders.my')->with('success', 'Review submitted successfully!');
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit($id)
    {
        $review = Review::with(['product', 'shade'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail(); // Simplified: will auto-redirect 404 if not found

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Match Blade 'name="image"'
        ]);

        $review->rating = $request->rating;
        $review->comment = $request->comment;

        if ($request->hasFile('image')) {
            // Delete the old physical file if a new one is being uploaded
            if ($review->photo_path && Storage::disk('public')->exists($review->photo_path)) {
                Storage::disk('public')->delete($review->photo_path);
            }
            
            // Store the new one and update the path
            $review->photo_path = $request->file('image')->store('reviews', 'public');
        }

        $review->save();

        return redirect()->route('reviews.edit', $review->id)->with('success', 'Review updated!');
    }

    /**
     * Delete a review.
     */
    public function destroy($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($review) {
            // Clean up the storage before deleting the record
            if ($review->photo_path && Storage::disk('public')->exists($review->photo_path)) {
                Storage::disk('public')->delete($review->photo_path);
            }
            
            $review->delete();
            return redirect()->back()->with('success', 'Review deleted.');
        }

        return redirect()->back()->with('error', 'Could not delete review.');
    }
}