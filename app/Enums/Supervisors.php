<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum Supervisors: string
{
    use BaseEnumTrait;
    case DELOWAR_HOSSAIN            = "DELOWAR HOSSAIN";
    case SANTOSH_CHARI              = "SANTOSH CHARI";
    case MUHAMMAD_IMRAN_ABDULSATHAR = "MUHAMMAD IMRAN ABDULSATHAR";
    case YEROLA_SHANKAR             = "YEROLA SHANKAR";
    public static function supervisorType()
    {
        $arr = [];
        $arr[] = Self::DELOWAR_HOSSAIN;
        $arr[] = Self::SANTOSH_CHARI;
        $arr[] = Self::MUHAMMAD_IMRAN_ABDULSATHAR;
        $arr[] = Self::YEROLA_SHANKAR;
        return $arr;
    }
}

