<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum BloodType: string
{
    use BaseEnumTrait;

    case A_POSITIVE     = "A+";
    case A_NEGATIVE     = "A-";
    case B_POSITIVE     = "B+";
    case B_NEGATIVE     = "B-";
    case AB_POSITIVE    = "AB+";
    case AB_NEGATIVE    = "AB-";
    case O_POSITIVE     = "O+";
    case O_NEGATIVE     = "O-";

    public static function bloodtype()
    {
        $arr = [];
        $arr[] = Self::A_POSITIVE;
        $arr[] = Self::A_NEGATIVE;
        $arr[] = Self::B_POSITIVE;
        $arr[] = Self::B_NEGATIVE;
        $arr[] = Self::AB_POSITIVE;
        $arr[] = Self::AB_NEGATIVE;
        $arr[] = Self::O_POSITIVE;
        $arr[] = Self::O_NEGATIVE;
        return $arr;
    }
}

