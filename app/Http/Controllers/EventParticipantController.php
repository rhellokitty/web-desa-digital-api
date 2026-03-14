<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoriesInterface;
use Exception;
use Illuminate\Http\Request;

class EventParticipantController extends Controller
{
    private EventParticipantRepositoriesInterface $eventParticipantRepositories;

    public function __construct(EventParticipantRepositoriesInterface $eventParticipantRepositories)
    {
        $this->eventParticipantRepositories = $eventParticipantRepositories;
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
}
