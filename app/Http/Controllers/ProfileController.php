<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoriesInterface;
use Exception;

class ProfileController extends Controller
{
    private ProfileRepositoriesInterface $profileRepositories;

    public function __construct(ProfileRepositoriesInterface $profileRepositories)
    {
        $this->profileRepositories = $profileRepositories;
    }

    public function index()
    {
        try {
            $profile = $this->profileRepositories->getProfile();

            if (!$profile) {
                return ResponseHelper::jsonResponse(false, 'Data Profile Tidak Ditemukan', null, 200);
            } else {
                return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diambil', ProfileResource::make($profile), 200);
            }
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(false, 'Data Profile Gagal Diambil', null, 500);
        }
    }

    public function store(ProfileStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $profile = $this->profileRepositories->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Ditambahkan', ProfileResource::make($profile), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Profile Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request = $request->validated();

        try {
            $profile = $this->profileRepositories->update($request);
            return ResponseHelper::jsonResponse(true, 'Data Profile Berhasil Diupdate', ProfileResource::make($profile), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Profile Gagal Diupdate',
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
