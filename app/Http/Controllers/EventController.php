<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    private EventRepositoriesInterface $eventRepositories;

    public function __construct(EventRepositoriesInterface $eventRepositories)
    {
        $this->eventRepositories = $eventRepositories;
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
            $evetns = $this->eventRepositories->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                PaginateResource::make($evetns, EventResource::class),
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
}
