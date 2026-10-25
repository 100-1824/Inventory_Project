<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

/**
 * One-off product catalogue import, reachable only while logged in.
 *
 * Render's free plan has no Shell/One-Off Jobs access, so there's no way
 * to run `mysql < file.sql` or an artisan command directly against the
 * database. This route lets the already-authenticated admin trigger the
 * import over HTTP instead. Remove this controller, its route, and
 * database/imports/ALL_PRODUCTS_IMPORT.sql once the import has run once.
 */
class AdminImportController extends Controller
{
    public function run()
    {
        $path = database_path('imports/ALL_PRODUCTS_IMPORT.sql');

        if (!file_exists($path)) {
            return response("Import file not found at {$path}. It may have already been removed after a previous run.", 404)
                ->header('Content-Type', 'text/plain');
        }

        $sql = file_get_contents($path);

        $before = DB::table('products')->count();

        try {
            DB::unprepared($sql);
        } catch (\Throwable $e) {
            return response(
                "Import failed: {$e->getMessage()}\n\n" .
                "If this says a duplicate SKU, the import (or part of it) already ran before. " .
                "Check products count below and remove the import route/file if it's already done.",
                500
            )->header('Content-Type', 'text/plain');
        }

        $after = DB::table('products')->count();

        return response(
            "Import finished.\n" .
            "Products before: {$before}\n" .
            "Products after:  {$after}\n" .
            "Inserted:        " . ($after - $before) . "\n\n" .
            "Now remove app/Http/Controllers/AdminImportController.php, its route in routes/web.php, " .
            "and database/imports/ALL_PRODUCTS_IMPORT.sql, then redeploy."
        )->header('Content-Type', 'text/plain');
    }
}
