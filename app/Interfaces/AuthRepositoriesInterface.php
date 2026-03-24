<?php

namespace App\Interfaces;

interface AuthRepositoriesInterface
{

    public function login(array $data);

    public function logout();

    public function me();
}
