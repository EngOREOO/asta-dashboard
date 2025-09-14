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
        Schema::table('assessment_questions', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['assessment_id']);
            
            // Modify the column to allow null values
            $table->unsignedBigInteger('assessment_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['assessment_id']);
            
            // Change back to not nullable
            $table->unsignedBigInteger('assessment_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('assessment_id')->references('id')->on('assessments')->onDelete('cascade');
        });
    }
};