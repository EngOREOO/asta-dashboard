<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get filter parameters
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));
            $categoryId = $request->get('category_id');
            $instructorId = $request->get('instructor_id');
            $priceRange = $request->get('price_range', 'all');

            // Convert to Carbon instances
            $startDate = Carbon::parse($dateFrom);
            $endDate = Carbon::parse($dateTo);

            // Build base query
            $baseQuery = Course::query();

            // Apply filters
            if ($dateFrom && $dateTo) {
                $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($categoryId) {
                $baseQuery->where('category_id', $categoryId);
            }

            if ($instructorId) {
                $baseQuery->where('instructor_id', $instructorId);
            }

            if ($priceRange === 'paid') {
                $baseQuery->where('price', '>', 0);
            } elseif ($priceRange === 'free') {
                $baseQuery->where('price', 0);
            }

            // Sales statistics
            $totalSales = (clone $baseQuery)->where('price', '>', 0)->sum('price');
            $totalCourses = (clone $baseQuery)->count();
            $paidCourses = (clone $baseQuery)->where('price', '>', 0)->count();
            $freeCourses = (clone $baseQuery)->where('price', 0)->count();

            // Sales by month for chart (last 12 months)
            $salesByMonth = Course::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(price) as total_sales'),
                    DB::raw('COUNT(*) as course_count')
                )
                ->whereBetween('created_at', [now()->subMonths(12), now()])
                ->where('price', '>', 0)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Top selling courses
            $topCourses = (clone $baseQuery)
                ->where('price', '>', 0)
                ->withCount('students')
                ->with(['category', 'instructor'])
                ->orderBy('students_count', 'desc')
                ->limit(10)
                ->get();

            // Sales by category
            $salesByCategory = Course::select(
                    'categories.name as category_name',
                    DB::raw('SUM(courses.price) as total_sales'),
                    DB::raw('COUNT(courses.id) as course_count')
                )
                ->leftJoin('categories', 'courses.category_id', '=', 'categories.id')
                ->whereBetween('courses.created_at', [$startDate, $endDate])
                ->where('courses.price', '>', 0)
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_sales', 'desc')
                ->get();

            // Recent sales
            $recentSales = (clone $baseQuery)
                ->where('price', '>', 0)
                ->with(['category', 'instructor'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Growth metrics
            $previousPeriodStart = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
            $previousPeriodEnd = $startDate->copy()->subDay();
            
            $previousSales = Course::where('price', '>', 0)
                ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
                ->sum('price');

            $growthRate = $previousSales > 0 
                ? (($totalSales - $previousSales) / $previousSales) * 100 
                : 0;

            // Chart data for Chart.js
            $chartData = [
                'labels' => $salesByMonth->pluck('month')->map(function($month) {
                    return Carbon::parse($month)->format('M Y');
                })->toArray(),
                'sales' => $salesByMonth->pluck('total_sales')->toArray(),
                'courses' => $salesByMonth->pluck('course_count')->toArray()
            ];

            // Get filter options
            $categories = \App\Models\Category::select('id', 'name')->orderBy('name')->get();
            $instructors = User::role('instructor')->select('id', 'name')->orderBy('name')->get();

            return view('reports.sales.index', compact(
                'totalSales',
                'totalCourses',
                'paidCourses',
                'freeCourses',
                'topCourses',
                'salesByCategory',
                'recentSales',
                'growthRate',
                'chartData',
                'startDate',
                'endDate',
                'categories',
                'instructors',
                'dateFrom',
                'dateTo',
                'categoryId',
                'instructorId',
                'priceRange'
            ));

        } catch (\Exception $e) {
            \Log::error('Sales Report Error: ' . $e->getMessage());
            
            return view('reports.sales.index', [
                'totalSales' => 0,
                'totalCourses' => 0,
                'paidCourses' => 0,
                'freeCourses' => 0,
                'topCourses' => collect(),
                'salesByCategory' => collect(),
                'recentSales' => collect(),
                'growthRate' => 0,
                'chartData' => ['labels' => [], 'sales' => [], 'courses' => []],
                'startDate' => now()->subDays(30),
                'endDate' => now(),
                'categories' => collect(),
                'instructors' => collect(),
                'dateFrom' => now()->subDays(30)->format('Y-m-d'),
                'dateTo' => now()->format('Y-m-d'),
                'categoryId' => null,
                'instructorId' => null,
                'priceRange' => 'all',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        // Implementation for Excel/PDF export
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Convert to Carbon instances if they're strings
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $sales = Course::where('price', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['category', 'instructor'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Here you would implement actual export logic
        // For now, return a success message
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $sales->count()
        ]);
    }
}