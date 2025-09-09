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
        if (! Schema::hasColumn('degrees', 'duration_months')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->integer('duration_months')->nullable()->after('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('degrees', 'duration_months')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->dropColumn('duration_months');
            });
        }
    }
};
