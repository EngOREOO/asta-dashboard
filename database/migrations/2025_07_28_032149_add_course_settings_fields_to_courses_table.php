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
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('allow_comments')->default(false);
            $table->boolean('allow_notes')->default(false);
            $table->boolean('allow_ratings')->default(false);
            $table->text('overview')->nullable();
            $table->text('prerequisites')->nullable();
            $table->text('learning_objectives')->nullable();
            $table->text('target_audience')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->string('language')->default('Arabic');
            $table->integer('estimated_duration')->nullable()->comment('Duration in minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'allow_comments',
                'allow_notes', 
                'allow_ratings',
                'overview',
                'prerequisites',
                'learning_objectives',
                'target_audience',
                'difficulty_level',
                'language',
                'estimated_duration'
            ]);
        });
    }
};
