<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole(Role::findByName('admin', 'sanctum'));

        User::create([
            'name' => 'Kepala Keluarga',
            'email' => 'headFamily@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('head-of-family');

        UserFactory::new()->count(15)->create();
    }
}
