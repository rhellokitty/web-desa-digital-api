<?php

namespace App\Interfaces;

interface SocialAssistanceRecipientRepositoriesInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    );

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    );

    public function create(
        array $data
    );
}
