<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'course'])
            ->latest()
            ->paginate(20);
            
        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'course']);
        return view('reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);

        return redirect()->route('reviews.show', $review)
            ->with('success', 'Review rejected successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
