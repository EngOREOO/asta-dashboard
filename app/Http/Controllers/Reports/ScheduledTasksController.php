<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduledTasksController extends Controller
{
    public function index(Request $request)
    {
        // Simulate scheduled tasks since Laravel doesn't expose cron job info directly
        $scheduledTasks = [
            [
                'name' => 'Daily Backup',
                'schedule' => '0 2 * * *',
                'description' => 'Daily database and file backup',
                'last_run' => Carbon::now()->subHours(6),
                'next_run' => Carbon::now()->addHours(18),
                'status' => 'completed',
                'duration' => '5m 30s'
            ],
            [
                'name' => 'Clean Temporary Files',
                'schedule' => '0 */6 * * *',
                'description' => 'Remove temporary files every 6 hours',
                'last_run' => Carbon::now()->subHours(2),
                'next_run' => Carbon::now()->addHours(4),
                'status' => 'completed',
                'duration' => '1m 15s'
            ],
            [
                'name' => 'Generate Reports',
                'schedule' => '0 0 * * *',
                'description' => 'Generate daily reports',
                'last_run' => Carbon::now()->subDay(),
                'next_run' => Carbon::now()->addHours(12),
                'status' => 'completed',
                'duration' => '8m 45s'
            ],
            [
                'name' => 'Send Email Notifications',
                'schedule' => '*/15 * * * *',
                'description' => 'Send pending email notifications',
                'last_run' => Carbon::now()->subMinutes(10),
                'next_run' => Carbon::now()->addMinutes(5),
                'status' => 'running',
                'duration' => '30s'
            ],
            [
                'name' => 'Update Statistics',
                'schedule' => '0 1 * * *',
                'description' => 'Update system statistics',
                'last_run' => Carbon::now()->subDay()->addHour(),
                'next_run' => Carbon::now()->addHours(13),
                'status' => 'failed',
                'duration' => '0s'
            ]
        ];

        $totalTasks = count($scheduledTasks);
        $completedTasks = collect($scheduledTasks)->where('status', 'completed')->count();
        $runningTasks = collect($scheduledTasks)->where('status', 'running')->count();
        $failedTasks = collect($scheduledTasks)->where('status', 'failed')->count();

        return view('reports.scheduled-tasks.index', compact(
            'scheduledTasks',
            'totalTasks',
            'completedTasks',
            'runningTasks',
            'failedTasks'
        ));
    }
}