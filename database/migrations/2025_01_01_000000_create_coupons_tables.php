<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('percentage', 5, 2)->default(0);
            $table->enum('applies_to', ['all', 'selected'])->default('selected');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('courses')) {
            Schema::create('coupon_course', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->cascadeOnDelete();
                $table->unique(['coupon_id', 'course_id']);
            });
        } else {
            // Fallback if courses table not yet created in migration order
            Schema::create('coupon_course', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('coupon_id');
                $table->unsignedBigInteger('course_id');
                $table->unique(['coupon_id', 'course_id']);
                $table->index('coupon_id');
                $table->index('course_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_course');
        Schema::dropIfExists('coupons');
    }
};


