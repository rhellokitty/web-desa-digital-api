<?php

namespace App\Repositories;

use App\Interfaces\FamilyMemberRepositoriesInterface;
use App\Models\FamilyMember;

class FamilyMemberRepositories implements FamilyMemberRepositoriesInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = FamilyMember::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        // $query = FamilyMember::query();

        if ($limit) {
            $query->limit($limit);
        }

        if ($execute) {
            return $query->get();
        }
        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }
}
