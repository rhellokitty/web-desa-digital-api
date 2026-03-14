<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoriesInterface;
use App\Models\EventParticipant;

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
}
