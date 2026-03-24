<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\FamilyMemberRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class FamilyMemberController extends Controller implements HasMiddleware
{
    private FamilyMemberRepositoriesInterface $familyMemberRepositories;

    public function __construct(FamilyMemberRepositoriesInterface $familyMemberRepositories)
    {
        $this->familyMemberRepositories = $familyMemberRepositories;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['family-member-list|family-member-create|family-member-edit|family-member-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['family-member-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['family-member-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['family-member-delete']), only: ['destroy']),
        ];
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
            $familyMember = $this->familyMemberRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Diambil', PaginateResource::make($familyMember, FamilyMemberResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Diambil', null, 500);
        }
    }

    public function show(string $id)
    {
        try {
            $familyMember = $this->familyMemberRepositories->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Family Member Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Diambil', FamilyMemberResource::make($familyMember), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Diambil', null, 500);
        }
    }

    public function store(FamilyMemberStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $familyMember = $this->familyMemberRepositories->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Ditambahkan', FamilyMemberResource::make($familyMember), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Ditambahkan', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    public function update(FamilyMemberUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepositories->getById(
                $id
            );

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Family Member Tidak Ditemukan', null, 404);
            }

            $familyMember = $this->familyMemberRepositories->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Diupdate', FamilyMemberResource::make($familyMember), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Diupdate', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $familyMember = $this->familyMemberRepositories->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Data Family Member Tidak Ditemukan', null, 404);
            }

            $familyMember = $this->familyMemberRepositories->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Family Member Berhasil Dihapus', FamilyMemberResource::make($familyMember), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Family Member Gagal Dihapus', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }
}
