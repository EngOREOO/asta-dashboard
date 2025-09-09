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
        Schema::table('course_comments', function (Blueprint $table) {
            $table->text('instructor_reply')->nullable()->after('content');
            $table->timestamp('replied_at')->nullable()->after('instructor_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_comments', function (Blueprint $table) {
            $table->dropColumn(['instructor_reply', 'replied_at']);
        });
    }
};
