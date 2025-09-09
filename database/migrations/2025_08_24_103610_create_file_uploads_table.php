<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->nullable()->constrained('course_materials')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');

            // File information
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type'); // video, pdf, ppt, doc, etc.
            $table->string('mime_type');
            $table->bigInteger('file_size'); // in bytes

            // Upload status and progress
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->integer('progress')->default(0); // 0-100 percentage
            $table->text('error_message')->nullable();

            // Video specific fields
            $table->integer('duration')->nullable(); // in seconds
            $table->string('thumbnail_path')->nullable();

            // Metadata
            $table->json('metadata')->nullable(); // Additional file info
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['course_id', 'status']);
            $table->index(['material_id', 'status']);
            $table->index(['uploaded_by', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
