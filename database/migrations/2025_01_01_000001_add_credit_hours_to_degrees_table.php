<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('degrees', function (Blueprint $table) {
            if (!Schema::hasColumn('degrees', 'credit_hours')) {
                $table->unsignedInteger('credit_hours')->nullable()->after('duration_months');
            }
        });
    }

    public function down(): void
    {
        Schema::table('degrees', function (Blueprint $table) {
            if (Schema::hasColumn('degrees', 'credit_hours')) {
                $table->dropColumn('credit_hours');
            }
        });
    }
};


