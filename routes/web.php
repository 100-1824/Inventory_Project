<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Redirect home to products
    Route::get('/', fn() => redirect()->route('products.index'));

    // Product resource routes
    Route::resource('products', ProductController::class);

    // Sales Routes
    Route::get('/sales/search-products', [SaleController::class, 'searchProducts'])->name('sales.search');
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Expenses Routes
    Route::resource('expenses', ExpenseController::class);

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
