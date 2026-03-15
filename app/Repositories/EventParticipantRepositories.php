<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoriesInterface;
use App\Models\Event;
use App\Models\EventParticipant;
use Exception;
use Illuminate\Support\Facades\DB;

class EventParticipantRepositories implements EventParticipantRepositoriesInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = EventParticipant::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        if ($limit) {
            $query->limit($limit);
        }

        if ($execute) {
            return $query->get();
        }
        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = EventParticipant::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            $event = Event::where('id', $data['event_id'])->first();

            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->payment_status = "pending";
            $eventParticipant->save();
            DB::commit();
            return $eventParticipant;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
