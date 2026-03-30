<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\DashboardRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardRepositoriesInterface $dashboardRepositories;

    public function __construct(DashboardRepositoriesInterface $dashboardRepositories)
    {
        $this->dashboardRepositories = $dashboardRepositories;
}

    public function getDashboardData()
    {
        try {
            $data = $this->dashboardRepositories->getDashboardData();

            return ResponseHelper::jsonResponse(true, 'Succes', $data, 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
