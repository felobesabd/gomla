<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum VehicleStatus: int
{
    use BaseEnumTrait;

    case RUNNING = 1;
    case IN_PROGRESS = 2;
    case STOP = 3;

    public static function vehicleStatus()
    {
        $arr = [];
        $arr[] = Self::RUNNING;
        $arr[] = Self::IN_PROGRESS;
        $arr[] = Self::STOP;
        return $arr;
    }
}

