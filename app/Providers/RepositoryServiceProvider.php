<?php

namespace App\Providers;

use App\Interfaces\EventRepositoriesInterface;
use App\Interfaces\FamilyMemberRepositoriesInterface;
use App\Interfaces\HeadOfFamilyRepositoriesInterface;
use App\Interfaces\SocialAssistanceRecipientRepositoriesInterface;
use App\Interfaces\SocialAssistanceRepositoriesInterface;
use App\Interfaces\UserRepositoriesInterface;
use App\Repositories\EventRepositories;
use App\Repositories\FamilyMemberRepositories;
use App\Repositories\HeadOfFamilyRepositories;
use App\Repositories\SocialAssistanceRecipientRepositories;
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
        $this->app->bind(SocialAssistanceRecipientRepositoriesInterface::class, SocialAssistanceRecipientRepositories::class);
        $this->app->bind(EventRepositoriesInterface::class, EventRepositories::class);
    }
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
