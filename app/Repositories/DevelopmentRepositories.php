<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoriesInterface;
use App\Models\Development;

class DevelopmentRepositories implements DevelopmentRepositoriesInterface
{

    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Development::where(function ($query) use ($search) {
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
        $query = Development::where('id', $id);
        return $query->first();
    }
}
