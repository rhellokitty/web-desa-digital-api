<?php

namespace App\Repositories;

use App\Interfaces\DashboardRepositoriesInterface;
use App\Models\Development;
use App\Models\Event;
use App\Models\FamilyMember;
use App\Models\HeadOfFamily;
use App\Models\SocialAssistance;

class DashboardRepositories implements DashboardRepositoriesInterface
{
    public function getDashboardData()
    {
        return [
            'residents' => HeadOfFamily::count() + FamilyMember::count(),
            'head_of_families' => HeadOfFamily::count(),
            'social_assistances' => SocialAssistance::count(),
            'events' => Event::count(),
            'developments' => Development::count(),
        ];
    }
}
