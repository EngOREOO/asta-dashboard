<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StorageConsumptionController extends Controller
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

        // Get actual storage usage
        $publicStorage = $this->getDirectorySize(public_path('storage'));
        $storageApp = $this->getDirectorySize(storage_path('app'));
        
        $totalStorage = $publicStorage + $storageApp;

        // Simulate storage by user (since we don't have actual tracking)
        $storageByUser = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('FLOOR(RAND() * 1000) as storage_used')
            )
            ->orderBy('storage_used', 'desc')
            ->limit(20)
            ->get();

        return view('reports.storage-consumption.index', compact(
            'totalStorage',
            'publicStorage',
            'storageApp',
            'storageByUser',
            'startDate',
            'endDate'
        ));
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        if (is_dir($directory)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        }
        return $size / (1024 * 1024); // Convert to MB
    }
}