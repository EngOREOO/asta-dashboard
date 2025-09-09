<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('degrees', function (Blueprint $table) {
            if (!Schema::hasColumn('degrees', 'code')) {
                $table->string('code', 50)->nullable()->unique()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('degrees', function (Blueprint $table) {
            if (Schema::hasColumn('degrees', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }
        });
    }
};


