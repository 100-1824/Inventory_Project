<?php

use App\Models\Product;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Checking products table...\n";
    if (Schema::hasTable('products')) {
        echo "Table 'products' exists.\n";
        $columns = Schema::getColumnListing('products');
        echo "Columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "Table 'products' DOES NOT exist.\n";
    }

    echo "\nAttempting to create a test product...\n";
    $product = Product::create([
        'name' => 'Test Product ' . time(),
        'sku' => 'TEST-' . time(),
        'category' => 'Hardware',
        'price' => 100,
        'quantity' => 10,
        'unit' => 'pcs',
        'rack_number' => 'TEST-01'
    ]);
    echo "Product created successfully! ID: " . $product->id . "\n";
    
    // Clean up
    $product->delete();
    echo "Test product deleted.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
