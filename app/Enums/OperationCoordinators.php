<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum OperationCoordinators: string
{
    use BaseEnumTrait;
    case AKHTAR_ALI_MASUD                       = "AKHTAR ALI MASUD";
    case SUKUMARAN_RAJESH                       = "SUKUMARAN RAJESH";
    case THUMBRAPULLY_KUMARAN_PRASANNA_KUMAR    = "THUMBRAPULLY KUMARAN PRASANNA KUMAR";
    case RAJENDRAN_NARAYANAN                    = "RAJENDRAN NARAYANAN";
    public static function coordinatorType()
    {
        $arr = [];
        $arr[] = Self::AKHTAR_ALI_MASUD;
        $arr[] = Self::SUKUMARAN_RAJESH;
        $arr[] = Self::THUMBRAPULLY_KUMARAN_PRASANNA_KUMAR;
        $arr[] = Self::RAJENDRAN_NARAYANAN;
        return $arr;
    }
}

