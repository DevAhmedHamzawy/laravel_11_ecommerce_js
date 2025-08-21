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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('qty', 10, 2);
            $table->enum('discount_sort', ['percentage' , 'amount'])->nullable();
            $table->integer('discount');
            $table->decimal('sub_total', 9 , 3);
            $table->decimal('vat', 9 , 3);
            $table->decimal('total', 9 , 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
