<?php

namespace App\Interfaces;

interface ProfileRepositoriesInterface
{
    public function getProfile();

    public function create(array $data);

    public function update(array $data);

    public function delete(string $id);
}
