<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('degrees', 'provider')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->string('provider')->nullable()->after('name_ar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('degrees', 'provider')) {
            Schema::table('degrees', function (Blueprint $table) {
                $table->dropColumn('provider');
            });
        }
    }
};


