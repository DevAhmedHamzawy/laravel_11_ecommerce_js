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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent']); // نوع الخصم
            $table->decimal('value', 8, 2); // قيمة الخصم
            $table->decimal('min_order', 8, 2)->default(0); // الحد الأدنى للطلب
            $table->integer('max_usage')->nullable(); // أقصى عدد استخدام
            $table->integer('used_count')->default(0); // عدد مرات الاستخدام
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
