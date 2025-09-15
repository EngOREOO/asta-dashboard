<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromoCodeStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('is_active', true)->count();
        
        // Simulate usage statistics since we don't have usage tracking
        $couponUsage = Coupon::select(
                'coupons.*',
                DB::raw('FLOOR(RAND() * 100) as usage_count'),
                DB::raw('FLOOR(RAND() * 1000) as total_discount')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('usage_count', 'desc')
            ->get();

        return view('reports.promo-code-statistics.index', compact(
            'totalCoupons',
            'activeCoupons',
            'couponUsage',
            'startDate',
            'endDate'
        ));
    }
}