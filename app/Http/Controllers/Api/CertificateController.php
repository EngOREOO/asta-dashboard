<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseMaterialProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Get student's certificates
     */
    public function index()
    {
        $user = auth()->user();
        $certificates = Certificate::where('user_id', $user->id)
            ->with(['course.instructor', 'course.category', 'course.degree'])
            ->latest('issued_at')
            ->get();

        return response()->json($certificates);
    }

    /**
     * Get specific certificate
     */
    public function show(Certificate $certificate)
    {
        // Ensure user can only access their own certificates
        if ($certificate->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($certificate->load(['course.instructor', 'course.category', 'course.degree']));
    }

    /**
     * Generate certificate for a completed course
     */
    public function generate(Course $course)
    {
        $user = auth()->user();

        // Check if user is enrolled in the course
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are not enrolled in this course'], 403);
        }

        // Check if certificate already exists
        if (Certificate::where('user_id', $user->id)->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'Certificate already exists for this course'], 409);
        }

        // Check if course is completed (all materials completed)
        $totalMaterials = $course->materials()->count();
        if ($totalMaterials === 0) {
            return response()->json(['message' => 'Course has no materials'], 400);
        }

        $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials()->pluck('id'))
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        if ($completedMaterials < $totalMaterials) {
            return response()->json([
                'message' => 'Course not completed. You have completed ' . $completedMaterials . ' out of ' . $totalMaterials . ' materials'
            ], 400);
        }

        // Generate certificate
        DB::transaction(function () use ($user, $course) {
            $certificate = Certificate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'issued_at' => now(),
            ]);

            // Generate PDF certificate
            $pdf = $this->generateCertificatePDF($certificate);
            $filename = 'certificates/certificate_' . $certificate->id . '.pdf';
            
            Storage::disk('public')->put($filename, $pdf->output());
            
            $certificate->update(['certificate_url' => $filename]);
        });

        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->with(['course.instructor', 'course.category', 'course.degree'])
            ->first();

        return response()->json([
            'message' => 'Certificate generated successfully',
            'certificate' => $certificate
        ], 201);
    }

    /**
     * Download certificate PDF
     */
    public function download(Certificate $certificate)
    {
        // Ensure user can only download their own certificates
        if ($certificate->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$certificate->certificate_url || !Storage::disk('public')->exists($certificate->certificate_url)) {
            return response()->json(['message' => 'Certificate file not found'], 404);
        }

        $url = Storage::disk('public')->url($certificate->certificate_url);
        
        return response()->json([
            'download_url' => $url,
            'filename' => 'certificate_' . $certificate->course->title . '.pdf'
        ]);
    }

    /**
     * Get certificate status for a course
     */
    public function status(Course $course)
    {
        $user = auth()->user();

        // Check if user is enrolled
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are not enrolled in this course'], 403);
        }

        // Check if certificate exists
        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        // Calculate progress
        $totalMaterials = $course->materials()->count();
        $completedMaterials = CourseMaterialProgress::whereIn('course_material_id', $course->materials()->pluck('id'))
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100, 2) : 0;
        $isCompleted = $totalMaterials > 0 && $completedMaterials >= $totalMaterials;

        return response()->json([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'total_materials' => $totalMaterials,
            'completed_materials' => $completedMaterials,
            'progress_percentage' => $progress,
            'is_completed' => $isCompleted,
            'certificate_exists' => $certificate !== null,
            'certificate' => $certificate ? $certificate->load(['course.instructor']) : null,
            'can_generate' => $isCompleted && $certificate === null
        ]);
    }

    /**
     * Generate PDF certificate
     */
    private function generateCertificatePDF(Certificate $certificate)
    {
        $certificate->load(['user', 'course.instructor']);

        $data = [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
            'instructor' => $certificate->course->instructor,
            'issued_date' => $certificate->issued_at->format('F j, Y'),
            'certificate_id' => 'CERT-' . str_pad($certificate->id, 6, '0', STR_PAD_LEFT)
        ];

        $pdf = PDF::loadView('certificates.template', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf;
    }

    /**
     * Get certificate statistics
     */
    public function stats()
    {
        $user = auth()->user();
        
        $totalCertificates = Certificate::where('user_id', $user->id)->count();
        $recentCertificates = Certificate::where('user_id', $user->id)
            ->with(['course.instructor'])
            ->latest('issued_at')
            ->take(5)
            ->get();

        return response()->json([
            'total_certificates' => $totalCertificates,
            'recent_certificates' => $recentCertificates
        ]);
    }
} 