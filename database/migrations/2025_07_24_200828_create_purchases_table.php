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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_number')->unique();
            $table->string('image')->nullable();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 9 , 3);
            $table->decimal('vat', 9 , 3);
            $table->decimal('total', 9 , 3);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
