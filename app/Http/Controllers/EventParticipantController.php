<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller
{
    private EventParticipantRepositoriesInterface $eventParticipantRepositories;

    public function __construct(EventParticipantRepositoriesInterface $eventParticipantRepositories)
    {
        $this->eventParticipantRepositories = $eventParticipantRepositories;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-participant-list|event-participant-create|event-participant-edit|event-participant-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-participant-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy']),
        ];
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $eventParticipans = $this->eventParticipantRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );
            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', PaginateResource::make($eventParticipans, EventParticipantResource::class), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Diambil',
                null,
                500
            );
        }
    }

    public function index(Request $request)
    {
        try {
            $eventParticipants = $this->eventParticipantRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', EventParticipantResource::collection($eventParticipants), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Diambil',
                null,
                500
            );
        }
    }

    public function show(string $id)
    {
        try {
            $eventParticipants = $this->eventParticipantRepositories->getById($id);

            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diambil', EventParticipantResource::make($eventParticipants), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Diambil',
                null,
                500
            );
        }
    }

    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $eventParticipants = $this->eventParticipantRepositories->create($request);
            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Ditambahkan', EventParticipantResource::make($eventParticipants), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $eventParticipants = $this->eventParticipantRepositories->getById($id);

            if (!$eventParticipants) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Event Participant Tidak Ditemukan',
                    null,
                    404
                );
            }

            $eventParticipants = $this->eventParticipantRepositories->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'Data Event Participant Berhasil Diupdate', EventParticipantResource::make($eventParticipants), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Diupdate',
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
            $eventParticipants = $this->eventParticipantRepositories->getById($id);

            if (!$eventParticipants) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Event Participant Tidak Ditemukan',
                    null,
                    404
                );
            }

            $eventParticipants = $this->eventParticipantRepositories->delete($id);
            return ResponseHelper::jsonResponse(
                true,
                'Data Event Participant Berhasil Dihapus',
                EventParticipantResource::make($eventParticipants),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Participant Gagal Dihapus',
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
