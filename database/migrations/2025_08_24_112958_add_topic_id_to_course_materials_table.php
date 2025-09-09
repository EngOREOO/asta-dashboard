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
            $table->foreignId('topic_id')->nullable()->after('course_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true)->after('topic_id'); // order column already exists

            // Make level_id nullable since lessons now belong to topics
            $table->foreignId('level_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_materials', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn(['topic_id', 'is_active']);

            // Revert level_id to required
            $table->foreignId('level_id')->nullable(false)->change();
        });
    }
};
