<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentStoreRequest;
use App\Http\Requests\DevelopmentUpdateRequest;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    private DevelopmentRepositoriesInterface $developmentRepositories;

    public function __construct(DevelopmentRepositoriesInterface $developmentRepositories)
    {
        $this->developmentRepositories = $developmentRepositories;
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $developments = $this->developmentRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Diambil',
                PaginateResource::make($developments, DevelopmentResource::class),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Diambil',
                null,
                500
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $developments = $this->developmentRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Diambil',
                DevelopmentResource::collection($developments),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Diambil',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function show(string $id)
    {
        try {
            $development = $this->developmentRepositories->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Development Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Diambil',
                DevelopmentResource::make($development),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Diambil',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function store(DevelopmentStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $development = $this->developmentRepositories->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Ditambahkan',
                DevelopmentResource::make($development),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(DevelopmentUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $development = $this->developmentRepositories->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Development Tidak Ditemukan',
                    null,
                    404
                );
            }

            $development = $this->developmentRepositories->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Diupdate',
                DevelopmentResource::make($development),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Diupdate',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $development = $this->developmentRepositories->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Development Tidak Ditemukan',
                    null,
                    404
                );
            }

            $development = $this->developmentRepositories->delete($id);
            return ResponseHelper::jsonResponse(
                true,
                'Data Development Berhasil Dihapus',
                DevelopmentResource::make($development),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Gagal Dihapus',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }
}
