<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum Vehicle: int
{
    use BaseEnumTrait;

    case FULL_INSURANCE = 1;
    case THIRD_PARTY = 2;

    public static function insurance_type()
    {
        $arr = [];
        $arr[] = Self::FULL_INSURANCE;
        $arr[] = Self::THIRD_PARTY;
        return $arr;
    }
}

