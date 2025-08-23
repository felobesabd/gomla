<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum VehiclePlateLogo: int
{
    use BaseEnumTrait;

    case Good = 1;
    case Intermediate = 2;
    case Need_Replacement = 3;

    public static function VehiclePlateAndLogo()
    {
        $arr = [];
        $arr[] = Self::Good;
        $arr[] = Self::Intermediate;
        $arr[] = Self::Need_Replacement;
        return $arr;
    }
}

