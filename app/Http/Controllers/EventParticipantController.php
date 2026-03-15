<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class EventParticipantController extends Controller
{
    private EventParticipantRepositoriesInterface $eventParticipantRepositories;

    public function __construct(EventParticipantRepositoriesInterface $eventParticipantRepositories)
    {
        $this->eventParticipantRepositories = $eventParticipantRepositories;
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
}
