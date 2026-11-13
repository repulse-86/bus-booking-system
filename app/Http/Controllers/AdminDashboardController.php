<?php

namespace App\Http\Controllers;

use App\Services\AdminDashboardService;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __construct(protected AdminDashboardService $adminDashboardService) {}

    public function index(): Response
    {
        $data = $this->adminDashboardService->getDashboardData();

        return inertia('Admin/Home', $data);
    }
}
