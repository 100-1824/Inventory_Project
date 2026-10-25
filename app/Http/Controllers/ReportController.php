<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
    ) {
    }

    public function index()
    {
        $data = $this->reportService->getDashboardData();

        return view('reports.index', $data);
    }
}
