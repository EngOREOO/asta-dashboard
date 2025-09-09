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
        Schema::create('in_video_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained('course_materials')->onDelete('cascade');
            $table->string('quiz_name');
            $table->text('description')->nullable();
            $table->string('timestamp'); // Format: "00:28:15" (HH:MM:SS)
            $table->integer('questions_count')->default(0);
            $table->json('questions')->nullable(); // Store quiz questions and answers
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_video_quizzes');
    }
};
