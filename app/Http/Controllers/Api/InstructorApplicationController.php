<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class InstructorApplicationController extends Controller
{
    /**
     * Submit instructor application (Student only)
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is a student
        if (!$user->hasRole('student')) {
            return response()->json(['message' => 'Only students can apply to become instructors'], 403);
        }

        // Check if user already has a pending application
        if ($user->instructorApplication && $user->instructorApplication->isPending()) {
            return response()->json(['message' => 'You already have a pending application'], 409);
        }

        // Check if user can reapply (if previously rejected)
        if ($user->instructorApplication && $user->instructorApplication->isRejected()) {
            if (!$user->instructorApplication->canReapply()) {
                return response()->json([
                    'message' => 'You can reapply after 30 days from rejection',
                    'can_reapply_after' => $user->instructorApplication->getNextReapplyDate()
                ], 403);
            }
        }

        $validated = $request->validate([
            'field' => 'required|string|in:' . implode(',', InstructorApplication::$availableFields),
            'job_title' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'bio' => 'required|string|max:500',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handle CV file upload
        $cvUrl = null;
        if ($request->hasFile('cv_file')) {
            $cvUrl = $request->file('cv_file')->store('instructor-applications/cv', 'public');
        }

        // Create application
        $application = InstructorApplication::create([
            'user_id' => $user->id,
            'field' => $validated['field'],
            'job_title' => $validated['job_title'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'],
            'cv_url' => $cvUrl,
        ]);

        return response()->json([
            'success' => true,
            'application_id' => $application->id,
            'message' => 'Application submitted successfully',
            'next_steps' => 'Admin review within 48h'
        ], 201);
    }

    /**
     * Get user's application status
     */
    public function show()
    {
        $user = auth()->user();
        
        $application = $user->instructorApplication;
        
        if (!$application) {
            return response()->json(['message' => 'No application found'], 404);
        }

        return response()->json([
            'application' => $application->load('user:id,name,email'),
            'can_reapply' => $application->canReapply(),
            'next_reapply_date' => $application->getNextReapplyDate(),
        ]);
    }

    /**
     * Get available fields for applications
     */
    public function getAvailableFields()
    {
        return response()->json([
            'fields' => InstructorApplication::$availableFields
        ]);
    }

    /**
     * Admin: Get all applications with filters
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = InstructorApplication::with(['user:id,name,email', 'reviewer:id,name']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('field')) {
            $query->where('field', $request->field);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('submitted_at')->paginate(15);

        return response()->json([
            'applications' => $applications,
            'stats' => [
                'pending' => InstructorApplication::pending()->count(),
                'approved' => InstructorApplication::approved()->count(),
                'rejected' => InstructorApplication::rejected()->count(),
            ]
        ]);
    }

    /**
     * Admin: Review application (approve/reject)
     */
    public function review(Request $request, InstructorApplication $application)
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if application is already reviewed
        if (!$application->isPending()) {
            return response()->json(['message' => 'Application has already been reviewed'], 400);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($application, $validated) {
            $application->update([
                'status' => $validated['status'],
                'admin_feedback' => $validated['feedback'],
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $user = $application->user;

            if ($validated['status'] === 'approved') {
                // Add instructor role to user
                $user->assignRole('instructor');
                
                // Send approval email
                $this->sendApprovalEmail($user, $application);
                
                return response()->json([
                    'message' => 'Application approved successfully',
                    'user_role_updated' => true,
                    'email_sent' => true
                ]);
            } else {
                // Send rejection email
                $this->sendRejectionEmail($user, $application);
                
                return response()->json([
                    'status' => 'rejected',
                    'feedback' => $validated['feedback'],
                    'can_reapply_after' => $application->getNextReapplyDate(),
                    'email_sent' => true
                ]);
            }
        });

        return response()->json([
            'message' => 'Application reviewed successfully'
        ]);
    }

    /**
     * Admin: Get application details
     */
    public function showAdmin(InstructorApplication $application)
    {
        // Check if user is admin
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'application' => $application->load([
                'user:id,name,email,created_at',
                'reviewer:id,name'
            ])
        ]);
    }

    /**
     * Send approval email
     */
    private function sendApprovalEmail(User $user, InstructorApplication $application)
    {
        // In a real application, you would use Laravel's Mail facade
        // For now, we'll just log the email
        \Log::info("Approval email sent to {$user->email} for application {$application->id}");
        
        // Example email content:
        $emailData = [
            'user_name' => $user->name,
            'field' => $application->field,
            'job_title' => $application->job_title,
            'approval_date' => now()->format('Y-m-d'),
        ];

        // Mail::to($user->email)->send(new InstructorApprovalMail($emailData));
    }

    /**
     * Send rejection email
     */
    private function sendRejectionEmail(User $user, InstructorApplication $application)
    {
        // In a real application, you would use Laravel's Mail facade
        // For now, we'll just log the email
        \Log::info("Rejection email sent to {$user->email} for application {$application->id}");
        
        // Example email content:
        $emailData = [
            'user_name' => $user->name,
            'field' => $application->field,
            'feedback' => $application->admin_feedback,
            'reapply_date' => $application->getNextReapplyDate(),
        ];

        // Mail::to($user->email)->send(new InstructorRejectionMail($emailData));
    }
} 