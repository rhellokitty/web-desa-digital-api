<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\HeadOfFamilyStoreRequest;
use App\Http\Requests\HeadOfFamilyUpdateRequest;
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

    public function show(string $id)
    {
        try {
            $headOfFamily = $this->headOfFamilyRepositories->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Data Head Of Family Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Diambil', HeadOfFamilyResource::make($headOfFamily), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Diambil', null, 500);
        }
    }

    public function store(HeadOfFamilyStoreRequest $request)
    {
        $request = $request->validated();
        try {

            $headOfFamily = $this->headOfFamilyRepositories->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Ditambahkan', HeadOfFamilyResource::make($headOfFamily), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Ditambahkan', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    public function update(HeadOfFamilyUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $headOfFamily = $this->headOfFamilyRepositories->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Data Head Of Family Tidak Ditemukan', null, 404);
            }

            $headOfFamily = $this->headOfFamilyRepositories->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Diupdate', HeadOfFamilyResource::make($headOfFamily), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Diupdate', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $headOfFamily = $this->headOfFamilyRepositories->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(false, 'Data Head Of Family Tidak Ditemukan', null, 404);
            }

            $headOfFamily = $this->headOfFamilyRepositories->destroy($id);

            return ResponseHelper::jsonResponse(true, 'Data Head Of Family Berhasil Dihapus', HeadOfFamilyResource::make($headOfFamily), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Head Of Family Gagal Dihapus', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
        }
    }
}
