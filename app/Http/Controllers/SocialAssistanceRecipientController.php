<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecepientResource;
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

    public function index(Request $request)
    {
        try {
            $socialAssistanceRecepients = $this->socialAssistanceRecipientRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recepient Berhasil Diambil', SocialAssistanceRecepientResource::collection($socialAssistanceRecepients), 200);
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
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recepient Berhasil Diambil', PaginateResource::make($socialAssistances, SocialAssistanceRecepientResource::class), 200);
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

    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepositories->create($request);
            return ResponseHelper::jsonResponse(true, 'Data Social Assistance Recipient Berhasil Ditambahkan', SocialAssistanceRecepientResource::make($socialAssistanceRecipients), 200);
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
}
