<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('certificates', 'code')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->string('code')->unique()->nullable()->after('course_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('certificates', 'code')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            });
        }
    }
};


