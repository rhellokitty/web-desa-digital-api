<?php

namespace App\Providers;

use App\Interfaces\FamilyMemberRepositoriesInterface;
use App\Interfaces\HeadOfFamilyRepositoriesInterface;
use App\Interfaces\SocialAssistanceRepositoriesInterface;
use App\Interfaces\UserRepositoriesInterface;
use App\Repositories\FamilyMemberRepositories;
use App\Repositories\HeadOfFamilyRepositories;
use App\Repositories\SocialAssistanceRepositories;
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
        $this->app->bind(FamilyMemberRepositoriesInterface::class, FamilyMemberRepositories::class);
        $this->app->bind(SocialAssistanceRepositoriesInterface::class, SocialAssistanceRepositories::class);
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
