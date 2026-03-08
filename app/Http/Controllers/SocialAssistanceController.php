<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceStoreRequest;
use App\Http\Requests\SocialAssistanceUpdateRequest;
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

    public function show(string $id)
    {
        try {
            $socialAssistance = $this->socialAssistanceRepositories->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Social Assistance Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Berhasil Diambil', new SocialAssistanceResource($socialAssistance), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Social Assistance Gagal Diambil', null, 500);
        }
    }

    public function store(SocialAssistanceStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepositories->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Ditambahkan', new SocialAssistanceResource($socialAssistance), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(SocialAssistanceUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepositories->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }


            $socialAssistance =  $this->socialAssistanceRepositories->update(
                $id,
                $request
            );

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Diupdate', new SocialAssistanceResource($socialAssistance), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Bantuan Sosial Gagal Diupdate',
                [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ],
                500

            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $socialAssistance = $this->socialAssistanceRepositories->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 500);
            }

            $socialAssistance = $this->socialAssistanceRepositories->delete($id);
            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Dihapus', new SocialAssistanceResource($socialAssistance), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonresponse(false, 'Data Bantuan Sosial Gagal Dihapus', null, 500);
        }
    }
}
