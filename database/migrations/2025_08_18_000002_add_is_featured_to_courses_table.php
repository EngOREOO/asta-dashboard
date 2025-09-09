<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('courses', 'is_featured')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->boolean('is_featured')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('courses', 'is_featured')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }
};


