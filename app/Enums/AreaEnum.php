<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self area1()
 * @method static self area2()
 * @method static self area3()
 * @method static self area4()
 * @method static self area5()
 * @method static self area6()
 * @method static self area7()
 * @method static self area8()
 * @method static self area9()
 * @method static self area10()
 */
class AreaEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'area1' => 1,
            'area2' => 2,
            'area3' => 3,
            'area4' => 4,
            'area5' => 5,
            'area6' => 6,
            'area7' => 7,
            'area8' => 8,
            'area9' => 9,
            'area10' => 10,
        ];
    }

    protected static function labels(): array
    {
        return [
            'area1' => "Area 1 - Vision, Mission, Goals and Objectives",
            'area2' => "Area 2 – Faculty",
            'area3' => "Area 3 – Curriculum and Instruction",
            'area4' => "Area 4 – Support to Students",
            'area5' => "Area 5 – Research",
            'area6' => "Area 6 – Extensions and Community Involvement",
            'area7' => "Area 7 – Library",
            'area8' => "Area 8 – Physical Plant and Facilities",
            'area9' => "Area 9 – Laboratories",
            'area10' => "Area 10 – Administration",
        ];
    }
}
