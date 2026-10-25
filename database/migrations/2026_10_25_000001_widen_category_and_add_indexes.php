<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The products.category column was created as an ENUM('Hardware','Tools',
     * 'Sanitary','Paint') — it never included 'Electrical'/'Plumbing' even
     * though the app validated against those, and it can't hold the dozens
     * of real categories from the supplier catalogue imports (Power Tools,
     * UPVC Pipe, Diamond & TCT Cutters, etc). Widen it to a plain string.
     *
     * Also adds indexes that were missing for the columns the product list
     * filters/searches/sorts by.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 100)->default('Hardware')->change();
        });

        $this->addIndexIfMissing('products', 'category', 'products_category_index');
        $this->addIndexIfMissing('products', 'name', 'products_name_index');
        $this->addIndexIfMissing('products', 'supplier_name', 'products_supplier_name_index');
        $this->addIndexIfMissing('products', 'quantity', 'products_quantity_index');
        // sku already has a unique index from the create_products_table migration.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_index');
            $table->dropIndex('products_name_index');
            $table->dropIndex('products_supplier_name_index');
            $table->dropIndex('products_quantity_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->enum('category', ['Hardware', 'Tools', 'Sanitary', 'Paint'])->default('Hardware')->change();
        });
    }

    /**
     * Add an index only if it doesn't already exist, so this migration is
     * safe to run against a database that already had some of these indexes
     * added by hand (as happened previously on the InfinityFree database).
     */
    protected function addIndexIfMissing(string $table, string $column, string $indexName): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite has no easy "SHOW INDEX"; just attempt and ignore duplicates.
            try {
                Schema::table($table, function (Blueprint $t) use ($column, $indexName) {
                    $t->index($column, $indexName);
                });
            } catch (\Throwable $e) {
                // index likely already exists — ignore
            }
            return;
        }

        $exists = collect(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]))->isNotEmpty();

        if (!$exists) {
            Schema::table($table, function (Blueprint $t) use ($column, $indexName) {
                $t->index($column, $indexName);
            });
        }
    }
};
