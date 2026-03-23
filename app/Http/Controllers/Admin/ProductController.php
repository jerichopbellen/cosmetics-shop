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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

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
        ]);

        try {
            $product = Product::create($request->only(['name', 'brand_id', 'category_id', 'description', 'finish']));

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('products/gallery', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            if ($request->has('shades')) {
                foreach ($request->shades as $index => $data) {
                    $shade = new Shade();
                    $shade->product_id = $product->id;
                    $shade->shade_name = $data['shade_name'];
                    $shade->hex_code   = $data['hex_code'];
                    $shade->price      = $data['price'];
                    $shade->stock      = $data['stock'];

                    if ($request->hasFile("shades.$index.image")) {
                        $shade->image_path = $request->file("shades.$index.image")->store('products/shades', 'public');
                    }
                    $shade->save();
                }
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Product $product) {
        $brands = Brand::all();
        $categories = Category::all();
        $product->load('shades');
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['name', 'brand_id', 'category_id', 'description', 'finish']));

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $img = ProductImage::find($imageId);
                if ($img && $img->image_path && $img->image_path !== '/placeholders/product.png') {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }

        $keepShadeIds = [];
        if ($request->has('shades')) {
            foreach ($request->shades as $index => $data) {
                if (empty($data['shade_name'])) continue;

                $shadeId = isset($data['id']) ? (int)$data['id'] : 0;
                $shade = ($shadeId > 0) ? Shade::find($shadeId) : new Shade(['product_id' => $product->id]);

                $shadeData = $data;
                unset($shadeData['image']); 
                $shade->fill($shadeData);

                if ($request->hasFile("shades.$index.image")) {
                    if ($shade->image_path && $shade->image_path !== '/placeholders/product.png') {
                        Storage::disk('public')->delete($shade->image_path);
                    }
                    $shade->image_path = $request->file("shades.$index.image")->store('products/shades', 'public');
                }
                
                $shade->save();
                $keepShadeIds[] = $shade->id;
            }
        }

        $product->shades()->whereNotIn('id', $keepShadeIds)->delete();

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Soft delete: sets deleted_at, hides it from normal queries
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product archived successfully!');
    }

    public function restore($id)
    {
        // Finds even the soft-deleted one
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')->with('success', 'Product restored successfully!');
    }

    public function trash(ProductDataTable $dataTable)
    {
        return $dataTable->with(['only_trashed' => true])->render('admin.products.trash');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import error: ' . $e->getMessage());
        }
    }
}