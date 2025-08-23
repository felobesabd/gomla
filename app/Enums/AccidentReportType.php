<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum AccidentReportType: int
{
    use BaseEnumTrait;

    case internal_accident = 0;
    case accident_between_two_vehicles = 1;
    case insurance_accident = 2;


    public static function accidentReportTypes() :array
    {
        $arr = [];
        $arr[] = self::internal_accident;
        $arr[] = self::accident_between_two_vehicles;
        $arr[] = self::insurance_accident;

        return $arr;
    }
}

