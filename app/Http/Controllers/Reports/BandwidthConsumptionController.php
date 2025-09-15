<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BandwidthConsumptionController extends Controller
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

        // Simulate bandwidth data since we don't have actual tracking
        $bandwidthData = [];
        $totalBandwidth = 0;
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bandwidth = rand(1000, 5000); // GB
            $bandwidthData[] = [
                'month' => $date->format('Y-m'),
                'bandwidth' => $bandwidth
            ];
            $totalBandwidth += $bandwidth;
        }

        $chartData = [
            'labels' => collect($bandwidthData)->pluck('month')->map(function($month) {
                return Carbon::parse($month)->format('M Y');
            })->toArray(),
            'bandwidth' => collect($bandwidthData)->pluck('bandwidth')->toArray()
        ];

        return view('reports.bandwidth-consumption.index', compact(
            'totalBandwidth',
            'chartData',
            'startDate',
            'endDate'
        ));
    }
}