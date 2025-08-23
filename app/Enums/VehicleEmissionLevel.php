<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum VehicleEmissionLevel: string
{
    use BaseEnumTrait;

    case EURO_1 = 'EURO 1';
    case EURO_2 = 'EURO 2';
    case EURO_3 = 'EURO 3';
    case EURO_4 = 'EURO 4';
    case EURO_5 = 'EURO 5';
    case EURO_6 = 'EURO 6';
    case EURO_7 = 'EURO 7';

    public static function VehicleEmission()
    {
        $arr = [];
        $arr[] = Self::EURO_1;
        $arr[] = Self::EURO_2;
        $arr[] = Self::EURO_3;
        $arr[] = Self::EURO_4;
        $arr[] = Self::EURO_5;
        $arr[] = Self::EURO_6;
        $arr[] = Self::EURO_7;
        return $arr;
    }
}

