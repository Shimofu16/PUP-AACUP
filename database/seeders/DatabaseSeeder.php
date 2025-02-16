<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleAndPermissionSeeder::class,
            ProgramSeeder::class,
        ]);
        $role = Role::where('name', 'admin')->first();
        $user =  User::factory()->create([
            'name' => 'pup-administrator',
            'email' => 'pup-admin@pup-aacup.com',
        ]);
        $user->assignRole($role);
        $role = Role::where('name', 'faculty')->first();
        $user =  User::factory()->create([
            'name' => 'pup-faculty',
            'email' => 'pup-faculty@pup-aacup.com',
        ]);
        $user->assignRole($role);
    }
}
