<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Shade;
use App\Models\ProductImage;
use App\DataTables\ProductDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable) {
        return $dataTable->render('admin.products.index');
    }

    public function create() {
        $brands = Brand::all();
        $categories = Category::all();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'shades.*.shade_name' => 'required',
            'shades.*.hex_code' => 'required',
            'shades.*.price' => 'required|numeric',
            'shades.*.stock' => 'required|integer',
            'gallery.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Optional: validate gallery files
        ]);

        try {
            $product = Product::create($request->only(['name', 'brand_id', 'category_id', 'description', 'finish']));

            // --- NEW: Handle Product Gallery (Multiple Images) ---
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('products/gallery', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            // --- Handle Shades ---
            if ($request->has('shades')) {
                foreach ($request->shades as $index => $data) {
                    $shade = new Shade();
                    $shade->product_id = $product->id;
                    $shade->shade_name = $data['shade_name'];
                    $shade->hex_code   = $data['hex_code'];
                    $shade->price      = $data['price'];
                    $shade->stock      = $data['stock'];

                    // Use the same robust file check as your update method
                    if ($request->hasFile("shades.$index.image")) {
                        $shade->image_path = $request->file("shades.$index.image")->store('products/shades', 'public');
                    }
                    
                    $shade->save();
                }
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product) {
        $brands = Brand::all();
        $categories = Category::all();
        $product->load('shades');
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Deleted!');
    }

    public function update(Request $request, Product $product)
    {
        // 1. Update Main Product Details
        $product->update($request->only(['name', 'brand_id', 'category_id', 'description', 'finish']));

        // 2. Handle Product Gallery (Your working logic)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $img = ProductImage::find($imageId);
                if ($img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }

        // 3. Handle Shades (The problem area)
        $keepShadeIds = [];
        if ($request->has('shades')) {
            foreach ($request->shades as $index => $data) {
                // Validation: Skip if shade name is missing
                if (empty($data['shade_name'])) continue;

                // Find existing or create new
                $shadeId = isset($data['id']) ? (int)$data['id'] : 0;
                $shade = ($shadeId > 0) 
                    ? Shade::find($shadeId) 
                    : new Shade(['product_id' => $product->id]);

                if (!$shade) $shade = new Shade(['product_id' => $product->id]);

                // IMPORTANT: Remove 'image' from $data so fill() doesn't see the file object
                $shadeData = $data;
                unset($shadeData['image']); 
                $shade->fill($shadeData);

                // Handle individual shade image
                // We use the $index from the loop to target the specific file in the request
                if ($request->hasFile("shades.$index.image")) {
                    // Delete old image if it exists
                    if ($shade->image_path) {
                        Storage::disk('public')->delete($shade->image_path);
                    }
                    // Store the new one
                    $shade->image_path = $request->file("shades.$index.image")->store('products/shades', 'public');
                }
                
                $shade->save();
                $keepShadeIds[] = $shade->id;
            }
        }

        // 4. Delete Shades removed from the UI
        $product->shades()->whereNotIn('id', $keepShadeIds)->delete();

        return redirect()->route('products.index')->with('success', 'Product and shades updated successfully!');
    }
}