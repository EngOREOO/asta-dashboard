<?php

namespace App\Http\Controllers;

use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorApplicationController extends Controller
{
    public function index()
    {
        $applications = InstructorApplication::with(['user', 'reviewer'])
            ->latest()
            ->paginate(20);
            
        return view('instructor-applications.index', compact('applications'));
    }

    public function show(InstructorApplication $instructorApplication)
    {
        $instructorApplication->load(['user', 'reviewer']);
        return view('instructor-applications.show', compact('instructorApplication'));
    }

    public function approve(Request $request, InstructorApplication $instructorApplication)
    {
        $instructorApplication->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        // Assign instructor role to user
        if ($instructorApplication->user) {
            try {
                $instructorApplication->user->assignRole('instructor');
            } catch (\Exception $e) {
                // Fallback if Spatie permissions not available
                // You might want to handle this differently based on your role system
            }
        }

        return redirect()->route('instructor-applications.show', $instructorApplication)
            ->with('success', 'Application approved successfully.');
    }

    public function reject(Request $request, InstructorApplication $instructorApplication)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $instructorApplication->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()->route('instructor-applications.show', $instructorApplication)
            ->with('success', 'Application rejected.');
    }
}
