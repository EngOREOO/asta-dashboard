<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class SystemSettingsController extends Controller
{
    public function index()
    {
        // Get current settings from config or database
        $settings = [
            'app_name' => config('app.name', 'Learning Management System'),
            'app_url' => config('app.url', 'http://localhost'),
            'timezone' => config('app.timezone', 'UTC'),
            'locale' => config('app.locale', 'en'),
            'mail_driver' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', '587'),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', ''),
            'registration_enabled' => true,
            'email_verification' => true,
            'max_file_upload' => '100MB',
            'session_lifetime' => config('session.lifetime', 120),
            'maintenance_mode' => false,
        ];

        return view('system-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'locale' => 'required|string|in:en,ar',
            'mail_driver' => 'required|string|in:smtp,mail,sendmail',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
            'registration_enabled' => 'boolean',
            'email_verification' => 'boolean',
            'max_file_upload' => 'required|string',
            'session_lifetime' => 'required|integer|min:1',
            'maintenance_mode' => 'boolean',
        ]);

        // In a real application, you would save these to a database table
        // For now, we'll just show a success message
        
        // Example of how you might update some settings:
        // Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        // Setting::updateOrCreate(['key' => 'app_url'], ['value' => $request->app_url]);
        // ... and so on

        return back()->with('success', 'System settings updated successfully.');
    }

    public function clearCache()
    {
        // Clear various Laravel caches
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');

        return back()->with('success', 'All caches cleared successfully.');
    }

    public function enableMaintenanceMode()
    {
        \Artisan::call('down');
        return back()->with('success', 'Maintenance mode enabled.');
    }

    public function disableMaintenanceMode()
    {
        \Artisan::call('up');
        return back()->with('success', 'Maintenance mode disabled.');
    }

    public function exportSettings()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'exported_at' => now()->toISOString(),
        ];

        $fileName = 'system_settings_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'disk_space' => $this->formatBytes(disk_free_space('/')),
        ];

        return view('system-settings.info', compact('info'));
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
