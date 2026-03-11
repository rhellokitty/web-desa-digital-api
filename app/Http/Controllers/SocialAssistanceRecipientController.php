<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipientUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class SocialAssistanceRecipientController extends Controller
{
    private SocialAssistanceRecipientRepositoriesInterface $socialAssistanceRecipientRepositories;

    public function __construct(SocialAssistanceRecipientRepositoriesInterface $socialAssistanceRecipient)
    {
        $this->socialAssistanceRecipientRepositories = $socialAssistanceRecipient;
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $socialAssistances = $this->socialAssistanceRecipientRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recepient Berhasil Diambil', PaginateResource::make($socialAssistances, SocialAssistanceRecipientResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Social Assistance Recepient Gagal Diambil',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $socialAssistanceRecepients = $this->socialAssistanceRecipientRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recepient Berhasil Diambil', SocialAssistanceRecipientResource::collection($socialAssistanceRecepients), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Social Assistance Recepient Gagal Diambil', [
                [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ],
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepositories->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(false, 'Data Social Assistance Recipient Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recipient Berhasil Diambil', SocialAssistanceRecipientResource::make($socialAssistanceRecipient), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Social Assistance Recipient Gagal Diambil',
                [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ],
                500
            );
        }
    }

    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->create($request);
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recipient Berhasil Ditambahkan', SocialAssistanceRecipientResource::make($socialAssistanceRecipients), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Social Assistance Recipient Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(SocialAssistanceRecipientUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->getById($id);

            if (!$socialAssistanceRecipients) {
                return ResponseHelper::jsonResponse(false, 'Data Social Assistance Recipient Tidak Ditemukan', null, 404);
            }

            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->update(
                $id,
                $request
            );

            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recipient Berhasil Diupdate', SocialAssistanceRecipientResource::make($socialAssistanceRecipients), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Social Assistance Recipient Gagal Diupdate',
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
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->getById($id);

            if (!$socialAssistanceRecipients) {
                return ResponseHelper::jsonResponse(false, 'Data Social Assistance Recipient Tidak Ditemukan', null, 404);
            }

            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recipient Berhasil Dihapus', SocialAssistanceRecipientResource::make($socialAssistanceRecipients), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Social Assistance Recipient Gagal Dihapus', null, 500);
        }
    }
}
