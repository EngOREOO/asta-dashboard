<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = DB::table('user_wishlist')
            ->join('users', 'user_wishlist.user_id', '=', 'users.id')
            ->join('courses', 'user_wishlist.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
            ->select(
                'user_wishlist.*',
                'users.name as user_name',
                'users.email as user_email',
                'courses.title as course_title',
                'courses.price as course_price',
                'categories.name as course_category',
                'instructors.name as instructor_name',
                DB::raw('EXISTS(SELECT 1 FROM course_user WHERE course_user.user_id = user_wishlist.user_id AND course_user.course_id = user_wishlist.course_id) as is_enrolled')
            )
            ->latest('user_wishlist.created_at')
            ->paginate(20);

        return view('wishlists.index', compact('wishlists'));
    }

    public function analytics()
    {
        $totalWishlists = DB::table('user_wishlist')->count();
        
        $popularCourses = DB::table('user_wishlist')
            ->join('courses', 'user_wishlist.course_id', '=', 'courses.id')
            ->leftJoin('users as instructors', 'courses.instructor_id', '=', 'instructors.id')
            ->select(
                'courses.title',
                'courses.price',
                'instructors.name as instructor_name',
                DB::raw('COUNT(*) as wishlist_count')
            )
            ->groupBy('courses.id', 'courses.title', 'courses.price', 'instructors.name')
            ->orderBy('wishlist_count', 'desc')
            ->take(10)
            ->get();

        $wishlistsByMonth = DB::table('user_wishlist')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $topWishlisters = DB::table('user_wishlist')
            ->join('users', 'user_wishlist.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', DB::raw('COUNT(*) as wishlist_count'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('wishlist_count', 'desc')
            ->take(10)
            ->get();

        $conversionRate = 0;
        if ($totalWishlists > 0) {
            $enrolledFromWishlist = DB::table('user_wishlist')
                ->join('course_user', function($join) {
                    $join->on('user_wishlist.user_id', '=', 'course_user.user_id')
                         ->on('user_wishlist.course_id', '=', 'course_user.course_id');
                })
                ->count();
            $conversionRate = round(($enrolledFromWishlist / $totalWishlists) * 100, 2);
        }

        return view('wishlists.analytics', compact(
            'totalWishlists',
            'popularCourses',
            'wishlistsByMonth',
            'topWishlisters',
            'conversionRate'
        ));
    }
}
