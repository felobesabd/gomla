<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum StationStopType: int
{
    use BaseEnumTrait;

    case Salalah = 1;
    case Ghaftain = 2;
    case Adam = 3;

    public static function stationStopType()
    {
        $arr = [];
        $arr[] = Self::Salalah;
        $arr[] = Self::Ghaftain;
        $arr[] = Self::Adam;
        return $arr;
    }
}

