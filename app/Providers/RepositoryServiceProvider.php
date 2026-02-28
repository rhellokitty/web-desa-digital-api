<?php

namespace App\Providers;

use App\Interfaces\HeadOfFamilyRepositoriesInterface;
use App\Interfaces\UserRepositoriesInterface;
use App\Repositories\HeadOfFamilyRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoriesInterface::class, UserRepositories::class);
        $this->app->bind(HeadOfFamilyRepositoriesInterface::class, HeadOfFamilyRepositories::class);
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
