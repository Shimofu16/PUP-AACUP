<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => "Area 1 - Vision, Mission, Goals and Objectives",
                'parameters' => [
                    "B - Dissemination and Acceptability",
                    "A - Statement of Vision, Mission, Goals and  Objectives",
                ],
            ],
            [
                'name' => "Area 2 – Faculty",
                'parameters' => [
                    "A -Academic Qualifications And Professional Experience",
                    "B -Recruitment, Selection And Orientation",
                    "C - Faculty Adequacy And Loading",
                    "D - Rank And Tenure",
                    "E - Faculty Development",
                    "F - Professional Performance And Scholarly Works",
                    "G - Salaries, Fringe Benefits",
                    "H - Professionalism",
                ]
            ],
            [
                'name' => "Area 3 – Curriculum and Instruction",
                'parameters' => [
                    "A - Curriculum And Program Of Studies",
                    "B - Instructional Process, Methodologies And Learning Opportunities",
                    "C - Assessment Of Academic Performance",
                    "D - Management Of Learning",
                    "E - Graduation Requirements",
                    "F - Administrative Support For Effective Instruction",

                ]
            ],
            [
                'name' => "Area 4 – Support to Students",
                'parameters' => [
                    "A - Student Services Program (Ssp)",
                    "B - Student Welfare",
                    "C - Student Development",
                    "D - Institutional Student And Programs And Services",
                    "E - Research, Monitoring And Evaluation",
                ]
            ],
            [
                'name' => "Area 5 – Research",
                'parameters' => [
                    "A - Priorities And Relevance",
                    "B - Funding And Other Resources",
                    "C - Implementation, Monitoring, Evaluation And Utilization Of Research Results/Outputs",
                    "D - Publication And Dissemination",
                ]
            ],
            [
                'name' => "Area 6 – Extensions and Community Involvement",
                'parameters' => [
                    "A - Priorities And Relevance",
                    "B - Planning, Implementation, Monitoring And Evaluation",
                    "C- Funding And Other Resources",
                    "D - Community Involvement And Participation In The Institution's Activities",
                ]
            ],
            [
                'name' => "Area 7 – Library",
                'parameters' => [
                    "A - Administration",
                    "B - Administrative Staff",
                    "C - Collection Development, Organization And Preservation",
                    "D - Services And Utilization",
                    "E - Physical Set-Up And Facilities",
                    "F - Financial Support",
                    "G - Linkages",
                ]
            ],
            [
                'name' => "Area 8 – Physical Plant and Facilities",
                'parameters' => [
                    "A - Campus",
                    "B - Buildings",
                    "C - Classrooms",
                    "D - Offices And Staff Rooms",
                    "E - Assembly, Athletic And Sports Facilities",
                    "F - Medical And Dental Clinic",
                    "G - Student Center",
                    "H - Food Services/Canteen/Cafeteria",
                    "I - Accreditation Center",
                    "J - Housing(Optional)",
                ]
            ],
            [
                'name' => "Area 9 – Laboratories",
                'parameters' => [
                    "A - Laboratories, Shops And Facilities",
                    "B - Equipment, Supplies And Materials",
                    "C - Maintenance",
                    "D - Special Provisions",
                ]
            ],
            [
                'name' => "Area 10 – Administration",
                'parameters' => [
                    "A- Organization",
                    "B- Academic Administration",
                    "C- Student Administration",
                    "D- Financial Management",
                    "E- Supply Management",
                    "F- Records Management",
                    "G- Institutional Planning And Development",
                    "H- Performance Of Administrative Personnel",
                ]
            ],
        ];

        foreach ($areas as $area) {
            $model = Area::create([
                'name' => $area['name'],
            ]);
            foreach ($area['parameters'] as $parameter) {
                $model->parameters()->create([
                    'name' => $parameter,
                ]);
            }
        }
    }
}
