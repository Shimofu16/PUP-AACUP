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
            'area1' => "Area 1",
            'area2' => "Area 2",
            'area3' => "Area 3",
            'area4' => "Area 4",
            'area5' => "Area 5",
            'area6' => "Area 6",
            'area7' => "Area 7",
            'area8' =>  "Area 8",
            'area9' =>  "Area 9",
            'area10' =>  "Area 10",
        ];
    }
}
