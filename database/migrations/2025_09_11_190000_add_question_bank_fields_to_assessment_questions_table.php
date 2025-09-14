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
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable()->after('points');
            $table->string('category')->nullable()->after('difficulty');
            $table->text('tags')->nullable()->after('category');
            $table->unsignedBigInteger('created_by')->nullable()->after('tags');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_questions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['difficulty', 'category', 'tags', 'created_by']);
        });
    }
};
