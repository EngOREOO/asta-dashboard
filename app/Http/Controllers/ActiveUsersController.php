<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ActiveUsersController extends Controller
{
    /**
     * Display active users count for dashboard overview
     */
    public function getActiveUsersCount()
    {
        $activeUsersCount = $this->getActiveUsersCountFromSessions();
        return response()->json(['count' => $activeUsersCount]);
    }

    /**
     * Display a listing of active users
     */
    public function index()
    {
        $activeUsers = $this->getActiveUsersWithDetails();
        
        return view('active-users.index', compact('activeUsers'));
    }

    /**
     * Force logout a specific user
     */
    public function forceLogout(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Clear user's sessions from database
            DB::table('sessions')
                ->where('user_id', $userId)
                ->delete();
            
            // If using file-based sessions, we can't easily clear them
            // But the middleware will handle expired sessions
            
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل خروج المستخدم بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل خروج المستخدم'
            ], 500);
        }
    }

    /**
     * Get active users count from sessions
     */
    private function getActiveUsersCountFromSessions()
    {
        $timeout = config('session.timeout', 30);
        $cutoffTime = time() - ($timeout * 60);
        
        if (config('session.driver') === 'database') {
            return DB::table('sessions')
                ->where('last_activity', '>', $cutoffTime)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id');
        }
        
        // For file-based sessions, we can't easily count active users
        // Return a placeholder or implement alternative method
        return 0;
    }

    /**
     * Get active users with details
     */
    private function getActiveUsersWithDetails()
    {
        $timeout = config('session.timeout', 30);
        $cutoffTime = time() - ($timeout * 60);
        
        if (config('session.driver') === 'database') {
            $activeSessions = DB::table('sessions')
                ->where('last_activity', '>', $cutoffTime)
                ->whereNotNull('user_id')
                ->get();
            
            $activeUsers = collect();
            
            foreach ($activeSessions as $session) {
                $user = User::find($session->user_id);
                if ($user) {
                    $activeUsers->push([
                        'user' => $user,
                        'session_id' => $session->id,
                        'last_activity' => $session->last_activity,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_seen' => $this->getLastSeenTime($session->last_activity)
                    ]);
                }
            }
            
            return $activeUsers->unique('user.id')->values();
        }
        
        // For file-based sessions, return empty collection
        return collect();
    }

    /**
     * Get human-readable last seen time
     */
    private function getLastSeenTime($timestamp)
    {
        $now = time();
        $diff = $now - $timestamp;
        
        if ($diff < 60) {
            return 'الآن';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "منذ {$minutes} دقيقة";
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "منذ {$hours} ساعة";
        } else {
            $days = floor($diff / 86400);
            return "منذ {$days} يوم";
        }
    }
}
