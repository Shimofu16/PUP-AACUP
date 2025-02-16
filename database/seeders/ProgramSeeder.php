<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'code' => 'BSENTREP',
                'name' => 'Bachelor of Science in Entrepreneurship',
                'description' => '',
                'image' => ''
            ],
            [
                'code' => 'BBTE',
                'name' => 'Bachelor in Business Teacher Education',
                'description' => '',
                'image' => ''
            ],
            [
                'code' => 'BSIT',
                'name' => 'Bachelor of Science in Information Technology',
                'description' => '',
                'image' => ''
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
