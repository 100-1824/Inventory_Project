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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->enum('category', ['Hardware', 'Tools', 'Sanitary', 'Paint'])->default('Hardware');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->string('unit')->default('pcs');
            
            // Supplier Information
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->string('supplier_address')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
