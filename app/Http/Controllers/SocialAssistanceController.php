<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceResource;
use App\Interfaces\SocialAssistanceRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class SocialAssistanceController extends Controller
{
    private SocialAssistanceRepositoriesInterface $socialAssistanceRepositories;

    public function __construct(SocialAssistanceRepositoriesInterface $socialAssistanceRepositories)
    {
        $this->socialAssistanceRepositories = $socialAssistanceRepositories;
    }

    public function index(Request $request)
    {
        try {
            $socialAssistances = $this->socialAssistanceRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Berhasil Diambil', SocialAssistanceResource::collection($socialAssistances), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Social Assistance Gagal Diambil', null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $socialAssistances = $this->socialAssistanceRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Berhasil Diambil', PaginateResource::make($socialAssistances, SocialAssistanceResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Social Assistance Gagal Diambil', null, 500);
        }
    }
}
