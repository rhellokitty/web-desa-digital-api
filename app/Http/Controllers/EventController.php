<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoriesInterface $eventRepositories;

    public function __construct(EventRepositoriesInterface $eventRepositories)
    {
        $this->eventRepositories = $eventRepositories;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-list|event-create|event-edit|event-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['event-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-delete']), only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        try {
            $events = $this->eventRepositories->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                EventResource::collection($events),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Gagal Diambil',
                null,
                500
            );
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $events = $this->eventRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                PaginateResource::make($events, EventResource::class),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Gagal Diambil',
                null,
                500
            );
        }
    }

    public function show(string $id)
    {
        try {
            $events = $this->eventRepositories->getById($id);

            if (!$events) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Event Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                EventResource::make($events),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'data Event Gagal Diambil',
                null,
                500
            );
        }
    }

    public function store(EventStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $events = $this->eventRepositories->create($request);
            return ResponseHelper::jsonResponse(true, 'Data Event Berhasil Ditambahkan', EventResource::make($events), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Gagal Ditambahkan',
                [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500
            );
        }
    }

    public function update(EventUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $events = $this->eventRepositories->getById($id);

            if (!$events) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Event Tidak Ditemukan',
                    null,
                    404
                );
            }

            $events = $this->eventRepositories->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diupdate',
                EventResource::make($events),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Gagal Diupdate',
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
            $events = $this->eventRepositories->getById($id);

            if (!$events) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Event Tidak Ditemukan',
                    null,
                    404
                );
            }

            $events = $this->eventRepositories->delete($id);
            return ResponseHelper::jsonResponse(true, 'Data Event Berhasil Dihapus', EventResource::make($events), 200);
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                'Data Event Gagal Dihapus',
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
