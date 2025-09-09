<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('course_learning_path')) {
            Schema::create('course_learning_path', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
                $table->foreignId('learning_path_id')->constrained()->onDelete('cascade');
                $table->integer('order')->default(0);
                $table->timestamps();
                $table->unique(['course_id', 'learning_path_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('course_learning_path')) {
            Schema::dropIfExists('course_learning_path');
        }
    }
};


