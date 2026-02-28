<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\HeadOfFamilyRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class HeadOfFamilyController extends Controller
{
    private HeadOfFamilyRepositoriesInterface $headOfFamilyRepositories;

    public function __construct(HeadOfFamilyRepositoriesInterface $headOfFamilyRepositories)
    {
        $this->headOfFamilyRepositories = $headOfFamilyRepositories;
    }

    public function index(Request $request)
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Diambil', HeadOfFamilyResource::collection($headOfFamilies), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Diambil', null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $headOfFamilies = $this->headOfFamilyRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Diambil', PaginateResource::make($headOfFamilies, HeadOfFamilyResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Diambil', null, 500);
        }
    }
}
