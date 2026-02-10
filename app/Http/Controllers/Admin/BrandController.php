<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\DataTables\BrandDataTable;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display the table using the Yajra Service Class.
     */
    public function index(BrandDataTable $dataTable)
    {
        return $dataTable->render('admin.brands.index');
    }

  /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand in database.
     */
public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name|max:255',
        ]);

        try {
            Brand::create($request->all());
            // This triggers the @if ($message = Session::get('success'))
            return redirect()->route('brands.index')->with('success', 'Brand created successfully!');
        } catch (\Exception $e) {
            // This triggers the @if ($message = Session::get('error'))
            return back()->with('error', 'Something went wrong while saving.');
        }
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand in database.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|max:255|unique:brands,name,' . $brand->id,
        ]);

        $brand->update($request->all());

        try {
            return redirect()->route('brands.index')->with('success', 'Brand updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong while updating.');
        }

    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        
        try {
            return redirect()->route('brands.index')->with('success', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong while deleting.');
        }
    }   
}