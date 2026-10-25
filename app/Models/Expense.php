<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'date',
        'category',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const CATEGORIES = [
        'Rent',
        'Utilities',
        'Salaries',
        'Supplies',
        'Maintenance',
        'Other',
    ];
}
