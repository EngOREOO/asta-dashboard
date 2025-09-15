<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date filters
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Convert to Carbon instances if they're strings
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Since we don't have a payments table, we'll simulate with course enrollments
        // In a real scenario, you'd have a payments table
        $totalPayments = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.price', '>', 0)
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->sum('courses.price');

        $totalTransactions = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.price', '>', 0)
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->count();

        // Payments by month
        $paymentsByMonth = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->select(
                DB::raw('DATE_FORMAT(course_user.created_at, "%Y-%m") as month'),
                DB::raw('SUM(courses.price) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('courses.price', '>', 0)
            ->whereBetween('course_user.created_at', [$startDate->copy()->subMonths(11), $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Payment status distribution (simulated)
        $paymentStatus = [
            'completed' => $totalTransactions * 0.85, // 85% completed
            'pending' => $totalTransactions * 0.10,   // 10% pending
            'failed' => $totalTransactions * 0.05     // 5% failed
        ];

        // Recent payments
        $recentPayments = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->select(
                'course_user.created_at as payment_date',
                'users.name as customer_name',
                'users.email as customer_email',
                'courses.title as course_title',
                'courses.price as amount',
                DB::raw("'completed' as status")
            )
            ->where('courses.price', '>', 0)
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->orderBy('course_user.created_at', 'desc')
            ->paginate(20);

        // Top paying customers
        $topCustomers = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(courses.price) as total_spent'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('courses.price', '>', 0)
            ->whereBetween('course_user.created_at', [$startDate, $endDate])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // Chart data
        $chartData = [
            'labels' => $paymentsByMonth->pluck('month')->map(function($month) {
                return Carbon::parse($month)->format('M Y');
            })->toArray(),
            'amounts' => $paymentsByMonth->pluck('total_amount')->toArray(),
            'transactions' => $paymentsByMonth->pluck('transaction_count')->toArray()
        ];

        return view('reports.payments.index', compact(
            'totalPayments',
            'totalTransactions',
            'paymentStatus',
            'recentPayments',
            'topCustomers',
            'chartData',
            'startDate',
            'endDate'
        ));
    }
}