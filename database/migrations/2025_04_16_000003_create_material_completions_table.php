<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_material_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->timestamps();

            // Ensure a user can only complete a material once
            $table->unique(['user_id', 'course_material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_completions');
    }
};
