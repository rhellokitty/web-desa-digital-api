<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\FamilyMemberRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    private FamilyMemberRepositoriesInterface $familyMemberRepositories;

    public function __construct(FamilyMemberRepositoriesInterface $familyMemberRepositories)
    {
        $this->familyMemberRepositories = $familyMemberRepositories;
    }

    public function index(Request $request)
    {
        try {
            $familyMembers = $this->familyMemberRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Diambil', FamilyMemberResource::collection($familyMembers), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Diambil', null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {

        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $headOfFamilies = $this->familyMemberRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Diambil', PaginateResource::make($headOfFamilies, FamilyMemberResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Diambil', null, 500);
        }
    }
}
