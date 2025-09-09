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
        Schema::table('course_user', function (Blueprint $table) {
            $table->integer('progress')->default(0); // Progress percentage
            $table->timestamp('completed_at')->nullable(); // When course was completed
            $table->string('grade')->nullable(); // Grade (A+, B, etc.)
            $table->text('notes')->nullable(); // Student notes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropColumn([
                'progress',
                'completed_at',
                'grade',
                'notes'
            ]);
        });
    }
};
