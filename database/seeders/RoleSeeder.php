<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);
        
        $allPermissions = Permission::all()->reject(function ($permission) {
            return in_array($permission->name, [
                'family-member-menu',
                'family-member-list',
                'family-member-create',
                'family-member-edit',
                'family-member-delete'
            ]);
        });
        
        $adminRole->syncPermissions($allPermissions);

        Role::firstOrCreate([
            'name' => 'head-of-family',
            'guard_name' => 'sanctum',
        ])->givePermissionTo([
            'dashboard-menu',

            'head-of-family-list',
            'head-of-family-edit',

            'family-member-menu',
            'family-member-list',
            'family-member-create',
            'family-member-edit',
            'family-member-delete',

            'social-assistance-menu',
            'social-assistance-list',

            'social-assistance-recipient-menu',
            'social-assistance-recipient-list',
            'social-assistance-recipient-create',
            'social-assistance-recipient-edit',
            'social-assistance-recipient-delete',

            'event-menu',
            'event-list',

            'event-participant-menu',
            'event-participant-list',
            'event-participant-create',
            'event-participant-edit',
            'event-participant-delete',

            'development-menu',
            'development-list',

            'development-applicant-menu',
            'development-applicant-list',
            'development-applicant-create',
            'development-applicant-edit',
            'development-applicant-delete',

            'profile-menu',
        ]);
    }
}
