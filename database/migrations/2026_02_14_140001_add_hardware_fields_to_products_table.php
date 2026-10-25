<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // $table->string('unit')->default('pcs')->after('price'); // Already exists in create_products_table
            $table->boolean('is_box_item')->default(false)->after('unit');
            $table->integer('items_per_box')->nullable()->after('is_box_item');
            $table->integer('box_quantity')->default(0)->after('items_per_box');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit', 'is_box_item', 'items_per_box', 'box_quantity']);
        });
    }
};
