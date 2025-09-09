<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['user', 'course.instructor', 'course.category'])
            ->latest()
            ->paginate(20);
            
        return view('certificates.index', compact('certificates'));
    }

    public function create()
    {
        $courses = Course::where('status', 'approved')->get();
        $users = User::all();
        
        return view('certificates.create', compact('courses', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'issued_at' => 'nullable|date',
            'certificate_url' => 'nullable|url',
        ]);

        $validated['issued_at'] = $validated['issued_at'] ?? now();

        $certificate = Certificate::create($validated);

        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'Certificate issued successfully.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'course.instructor', 'course.category']);
        return view('certificates.show', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'Certificate revoked successfully.');
    }

    public function bulkIssue(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $issued = 0;
        foreach ($validated['user_ids'] as $userId) {
            // Check if certificate already exists
            if (!Certificate::where('user_id', $userId)->where('course_id', $validated['course_id'])->exists()) {
                Certificate::create([
                    'user_id' => $userId,
                    'course_id' => $validated['course_id'],
                    'issued_at' => now(),
                ]);
                $issued++;
            }
        }

        return redirect()->route('certificates.index')
            ->with('success', "Issued {$issued} certificates successfully.");
    }
}
