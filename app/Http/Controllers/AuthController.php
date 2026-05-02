<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginStoreRequest;
use App\Interfaces\AuthRepositoriesInterface;

class AuthController extends Controller
{
    private AuthRepositoriesInterface $authRepositories;

    public function __construct(AuthRepositoriesInterface $authRepositories)
    {
        $this->authRepositories = $authRepositories;
    }

    public function login(LoginStoreRequest $request)
    {
        $request = $request->validated();

        return $this->authRepositories->login($request);
    }

    public function logout()
    {
        return $this->authRepositories->logout();
    }

    public function me()
    {
        return $this->authRepositories->me();
    }
}
