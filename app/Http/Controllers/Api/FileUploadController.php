<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Initiate a file upload
     */
    public function initiateUpload(Request $request, Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only upload files to courses that you created.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'file_name' => 'required|string|max:255',
            'file_type' => 'required|string|in:video,pdf,ppt,doc,image,other',
            'file_size' => 'required|integer|min:1',
            'mime_type' => 'required|string|max:100',
            'material_id' => 'nullable|exists:course_materials,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Create upload record
            $upload = FileUpload::create([
                'course_id' => $course->id,
                'material_id' => $request->material_id,
                'uploaded_by' => $user->id,
                'file_name' => $request->file_name,
                'original_name' => $request->file_name,
                'file_path' => '', // Will be set when upload completes
                'file_type' => $request->file_type,
                'mime_type' => $request->mime_type,
                'file_size' => $request->file_size,
                'status' => FileUpload::STATUS_PENDING,
                'progress' => 0,
            ]);

            // Generate upload URL for the client
            $uploadUrl = "/api/file-uploads/{$upload->id}/chunk";

            return response()->json([
                'message' => 'تم بدء عملية الرفع',
                'upload' => [
                    'id' => $upload->id,
                    'file_name' => $upload->file_name,
                    'status' => $upload->status,
                    'progress' => $upload->progress,
                    'upload_url' => $uploadUrl,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء بدء عملية الرفع',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload file chunk
     */
    public function uploadChunk(Request $request, FileUpload $upload)
    {
        $user = auth('sanctum')->user();

        // Verify ownership
        if ($upload->uploaded_by !== $user->id) {
            return response()->json([
                'message' => 'You can only upload to your own uploads.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'chunk' => 'required|file|max:10240', // 10MB max per chunk
            'chunk_number' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Update status to in progress
            if ($upload->status === FileUpload::STATUS_PENDING) {
                $upload->update([
                    'status' => FileUpload::STATUS_IN_PROGRESS,
                    'progress' => 0,
                ]);
            }

            // Store chunk
            $chunk = $request->file('chunk');
            $chunkPath = "uploads/chunks/{$upload->id}/chunk_{$request->chunk_number}";
            Storage::put($chunkPath, file_get_contents($chunk));

            // Calculate progress
            $progress = (($request->chunk_number + 1) / $request->total_chunks) * 100;
            $finalProgress = $progress > 100 ? 100 : $progress;
            $upload->update(['progress' => $finalProgress]);

            // If this is the last chunk, combine all chunks
            if ($request->chunk_number === $request->total_chunks - 1) {
                $this->combineChunks($upload, $request->total_chunks);
            }

            return response()->json([
                'message' => 'تم رفع الجزء بنجاح',
                'upload' => [
                    'id' => $upload->id,
                    'progress' => $upload->progress,
                    'status' => $upload->status,
                ],
            ]);

        } catch (\Exception $e) {
            $upload->update([
                'status' => FileUpload::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'حدث خطأ أثناء رفع الجزء',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Combine uploaded chunks into final file
     */
    private function combineChunks(FileUpload $upload, int $totalChunks): void
    {
        try {
            $finalPath = "uploads/{$upload->course_id}/{$upload->file_type}/".Str::random(40).'_'.$upload->file_name;
            $finalFile = Storage::path($finalPath);

            // Create directory if it doesn't exist
            Storage::makeDirectory(dirname($finalPath));

            // Combine chunks
            $finalHandle = fopen($finalFile, 'wb');

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = "uploads/chunks/{$upload->id}/chunk_{$i}";
                if (Storage::exists($chunkPath)) {
                    $chunkContent = Storage::get($chunkPath);
                    fwrite($finalHandle, $chunkContent);

                    // Clean up chunk
                    Storage::delete($chunkPath);
                }
            }

            fclose($finalHandle);

            // Clean up chunks directory
            Storage::deleteDirectory("uploads/chunks/{$upload->id}");

            // Update upload record
            $upload->update([
                'file_path' => $finalPath,
                'status' => FileUpload::STATUS_COMPLETED,
                'progress' => 100,
            ]);

            // If it's a video, extract duration and create thumbnail
            if ($upload->file_type === FileUpload::TYPE_VIDEO) {
                $this->processVideo($upload);
            }

        } catch (\Exception $e) {
            $upload->update([
                'status' => FileUpload::STATUS_FAILED,
                'error_message' => 'Failed to combine chunks: '.$e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Process video file to extract metadata
     */
    private function processVideo(FileUpload $upload): void
    {
        try {
            // Here you would use FFmpeg or similar to extract video duration
            // For now, we'll set a default duration
            $upload->update([
                'duration' => 0, // You can implement FFmpeg integration here
                'metadata' => [
                    'processed_at' => now()->toISOString(),
                    'video_codec' => 'unknown',
                    'audio_codec' => 'unknown',
                ],
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Failed to process video: '.$e->getMessage());
        }
    }

    /**
     * Get upload status
     */
    public function getStatus(FileUpload $upload)
    {
        $user = auth('sanctum')->user();

        // Verify ownership
        if ($upload->uploaded_by !== $user->id) {
            return response()->json([
                'message' => 'You can only view your own uploads.',
            ], 403);
        }

        return response()->json([
            'upload' => [
                'id' => $upload->id,
                'file_name' => $upload->file_name,
                'status' => $upload->status,
                'progress' => $upload->progress,
                'file_path' => $upload->file_path,
                'file_size' => $upload->formatted_file_size,
                'duration' => $upload->formatted_duration,
                'error_message' => $upload->error_message,
                'created_at' => $upload->created_at,
                'updated_at' => $upload->updated_at,
            ],
        ]);
    }

    /**
     * Cancel upload
     */
    public function cancelUpload(FileUpload $upload)
    {
        $user = auth('sanctum')->user();

        // Verify ownership
        if ($upload->uploaded_by !== $user->id) {
            return response()->json([
                'message' => 'You can only cancel your own uploads.',
            ], 403);
        }

        try {
            // Clean up any uploaded chunks
            if (Storage::exists("uploads/chunks/{$upload->id}")) {
                Storage::deleteDirectory("uploads/chunks/{$upload->id}");
            }

            // Delete the upload record
            $upload->delete();

            return response()->json([
                'message' => 'تم إلغاء عملية الرفع بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إلغاء عملية الرفع',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get course uploads
     */
    public function getCourseUploads(Course $course)
    {
        $user = auth('sanctum')->user();

        if (! $user->hasRole('instructor')) {
            return response()->json([
                'message' => 'غير مصرح لك بالوصول لهذه الصفحة',
            ], 403);
        }

        // Ensure instructor owns this course
        if ($course->instructor_id !== $user->id) {
            return response()->json([
                'message' => 'You can only view uploads for courses that you created.',
            ], 403);
        }

        $uploads = $course->fileUploads()
            ->with('uploader:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($upload) {
                return [
                    'id' => $upload->id,
                    'file_name' => $upload->file_name,
                    'file_type' => $upload->file_type,
                    'status' => $upload->status,
                    'progress' => $upload->progress,
                    'file_size' => $upload->formatted_file_size,
                    'duration' => $upload->formatted_duration,
                    'uploader' => $upload->uploader->name,
                    'created_at' => $upload->created_at,
                    'error_message' => $upload->error_message,
                ];
            });

        return response()->json([
            'uploads' => $uploads,
        ]);
    }
}
