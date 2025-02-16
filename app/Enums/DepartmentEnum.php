<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self science()
 * @method static self mathematics()
 * @method static self english()
 * @method static self social_studies()
 * @method static self pe_and_health()
 * @method static self arts()
 * @method static self computer_science()
 * @method static self guidance_and_counseling()
 * @method static self special_education()
 * @method static self administration()
 */
class DepartmentEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'science' => 'Science Department',
            'mathematics' => 'Mathematics Department',
            'english' => 'English Department',
            'social_studies' => 'Social Studies Department',
            'pe_and_health' => 'Physical Education and Health Department',
            'arts' => 'Arts Department',
            'computer_science' => 'Computer Science Department',
            'guidance_and_counseling' => 'Guidance and Counseling Department',
            'special_education' => 'Special Education Department',
            'administration' => 'Administration Office',
        ];
    }
}
