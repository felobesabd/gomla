<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum Brands: string
{
    use BaseEnumTrait;
    case Cummins    = "Cummins";
    case PRW        = "PRW";
    case DT         = "DT";
    case Elring     = "Elring";
    case Bosch      = "Bosch";
    case Hingest    = "Hingest";
    case Fleetguard = "Fleetguard";
    case DUNLOP     = "DUNLOP";
    case MECHILINE  = "MECHILINE";
    case YOKOHAMA   = "YOKOHAMA";
    case Febi      = "Febi";

    public static function brandType()
    {
        $arr = [];
        $arr[] = Self::Cummins;
        $arr[] = Self::PRW;
        $arr[] = Self::DT;
        $arr[] = Self::Elring;
        $arr[] = Self::Bosch;
        $arr[] = Self::Hingest;
        $arr[] = Self::Fleetguard;
        $arr[] = Self::DUNLOP;
        $arr[] = Self::MECHILINE;
        $arr[] = Self::YOKOHAMA;
        $arr[] = Self::Febi;
        return $arr;
    }
}

