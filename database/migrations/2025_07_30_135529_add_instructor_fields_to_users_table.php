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
        Schema::table('users', function (Blueprint $table) {
            $table->string('teaching_field')->nullable();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'teaching_field',
                'job_title',
                'phone',
                'district',
                'street',
                'city'
            ]);
        });
    }
};
