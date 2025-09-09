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
        Schema::table('course_materials', function (Blueprint $table) {
            // Add assessment_id field
            $table->foreignId('assessment_id')->nullable()->after('level_id')->constrained('assessments')->onDelete('set null');

            // Make file_path nullable
            $table->string('file_path')->nullable()->change();

            // Update the type enum to include new types
            $table->enum('type', ['video', 'pdf', 'image', 'other', 'quiz', 'document'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_materials', function (Blueprint $table) {
            // Remove assessment_id field
            $table->dropForeign(['assessment_id']);
            $table->dropColumn('assessment_id');

            // Make file_path required again
            $table->string('file_path')->nullable(false)->change();

            // Revert the type enum
            $table->enum('type', ['video', 'pdf', 'image', 'other'])->change();
        });
    }
};
