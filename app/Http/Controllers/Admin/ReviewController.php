<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\ReviewDataTable;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function index(ReviewDataTable $dataTable)
    {
        return $dataTable->render('admin.reviews.index');
    }


    public function show($id)
    {
        $review = Review::with(['user', 'product', 'shade'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return redirect()->route('reviews.index')
                ->with('success', 'Review has been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the review.');
        }
    }
}