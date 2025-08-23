<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum StationType: string
{
    use BaseEnumTrait;

    case In_Station = 'in_station';
    case Trip = 'trip';

    public static function stationType()
    {
        $arr = [];
        $arr[] = Self::In_Station;
        $arr[] = Self::Trip;
        return $arr;
    }
}

