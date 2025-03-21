<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Program;
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
            AreaSeeder::class
        ]);
        $role = Role::where('name', 'admin')->first();
        $user =  User::factory()->create([
            'name' => 'pup-administrator',
            'email' => 'pup-admin@pup-aaccup.com',
        ]);
        $user->assignRole($role);
        $role = Role::where('name', 'faculty')->first();
        $user =  User::factory()->create([
            'name' => 'pup-faculty',
            'email' => 'pup-faculty@pup-aaccup.com',
        ]);
        $user->assignRole($role);

        $areas = Area::inRandomOrder()->take(5)->pluck('id');
        $programs = Program::inRandomOrder()->take(5)->pluck('id');
        foreach($areas as $area){
            $user->areas()->create(['area_id' => $area]);
        }
        foreach($programs as $program){
            $user->programs()->create(['program_id' => $program]);
        }

        $role = Role::where('name', 'faculty')->first();
        $user =  User::factory()->create([
            'name' => 'pup-faculty-2',
            'email' => 'pup-faculty-2@pup-aaccup.com',
        ]);
        $user->assignRole($role);

        $role = Role::where('name', 'committee_reviewer')->first();
        $user =  User::factory()->create([
            'name' => 'pup-committee-reviewer',
            'email' => 'pup-committee-reviewer@pup-aaccup.com',
        ]);
        $user->assignRole($role);
    }
}
