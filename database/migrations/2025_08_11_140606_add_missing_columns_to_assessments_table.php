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
        Schema::table('assessments', function (Blueprint $table) {
            $table->integer('duration_minutes')->nullable()->after('type');
            $table->integer('max_attempts')->nullable()->after('duration_minutes');
            $table->integer('passing_score')->nullable()->after('max_attempts');
            $table->boolean('is_active')->default(true)->after('passing_score');
            $table->boolean('randomize_questions')->default(false)->after('is_active');
            $table->boolean('show_results_immediately')->default(false)->after('randomize_questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn([
                'duration_minutes',
                'max_attempts', 
                'passing_score',
                'is_active',
                'randomize_questions',
                'show_results_immediately'
            ]);
        });
    }
};
