<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentApplicantRepositoriesInterface;
use App\Models\Development;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Symfony\Component\HttpFoundation\RateLimiter\RequestRateLimiterInterface;

// use Symfony\Component\HttpFoundation\Request;

class DevelopmentApplicantController extends Controller implements HasMiddleware
{
    private DevelopmentApplicantRepositoriesInterface $developmentApplicantRepositories;

    public function __construct(DevelopmentApplicantRepositoriesInterface $developmentApplicantRepositories)
    {
        $this->developmentApplicantRepositories = $developmentApplicantRepositories;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['development-applicant-list|development-applicant-create|development-applicant-edit|development-applicant-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['development-applicant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['development-applicant-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['development-applicant-delete']), only: ['destroy']),
        ];
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $developments = $this->developmentApplicantRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Aplicant Berhasil Diambil',
                PaginateResource::make($developments, DevelopmentApplicantResource::class),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Diambil',
                null,
                500
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $developmentAplicant = $this->developmentApplicantRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Aplicant Berhasil Diambil',
                DevelopmentApplicantResource::collection($developmentAplicant),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Diambil',
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
            $developmentApplicant = $this->developmentApplicantRepositories->getById($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Aplicant Berhasil Diambil',
                DevelopmentApplicantResource::make($developmentApplicant),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Diambil',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function store(DevelopmentApplicantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $developmentApplicant = $this->developmentApplicantRepositories->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Aplicant Berhasil Ditambahkan',
                DevelopmentApplicantResource::make($developmentApplicant),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $developmentApplicant = $this->developmentApplicantRepositories->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Development Aplicant Tidak Ditemukan',
                    null,
                    404
                );
            }

            $developmentApplicant = $this->developmentApplicantRepositories->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Applicant Berhasil Diupdate',
                DevelopmentApplicantResource::make($developmentApplicant),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Diupdate',
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
            $developmentApplicant = $this->developmentApplicantRepositories->getById($id);

            if (!$developmentApplicant) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Development Aplicant Tidak Ditemukan',
                    null,
                    404
                );
            }

            $this->developmentApplicantRepositories->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Development Aplicant Berhasil Dihapus',
                DevelopmentApplicantResource::make($developmentApplicant),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Development Aplicant Gagal Dihapus',
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
