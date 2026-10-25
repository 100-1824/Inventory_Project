<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Expense;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get the monthly sales total for the current month.
     */
    public function getMonthlySales(): float
    {
        return (float) Sale::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
    }

    /**
     * Get the monthly expenses total for the current month.
     */
    public function getMonthlyExpenses(): float
    {
        return (float) Expense::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');
    }

    /**
     * Calculate monthly profit (sales - expenses).
     */
    public function getMonthlyProfit(): float
    {
        return $this->getMonthlySales() - $this->getMonthlyExpenses();
    }

    /**
     * Get dead stock: products not updated in 60+ days that still have quantity.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDeadStock(int $limit = 20)
    {
        return Product::where('updated_at', '<', Carbon::now()->subDays(60))
            ->where('quantity', '>', 0)
            ->take($limit)
            ->get();
    }

    /**
     * Assemble all report data in a single call.
     */
    public function getDashboardData(): array
    {
        $monthlySales = $this->getMonthlySales();
        $monthlyExpenses = $this->getMonthlyExpenses();

        return [
            'monthlySales' => $monthlySales,
            'monthlyExpenses' => $monthlyExpenses,
            'profit' => $monthlySales - $monthlyExpenses,
            'deadStock' => $this->getDeadStock(),
        ];
    }
}
