<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum Locations: string
{
    use BaseEnumTrait;

    case Garage     = "Garage";
    case Salalah    = "Salalah";
    case Mazyoona   = "Mazyoona";
    case Thumrait   = "Thumrait";
    case Haila      = "Haila";
    case Doka       = "Doka";
    case Muqshin    = "Muqshin";
    case Ghaftain   = "Ghaftain";
    case Haima      = "Haima";
    case Ghaba      = "Ghaba";
    case Adam       = "Adam";
    case Nizwa      = "Nizwa";
    case Muscat     = "Muscat";
    case UAE        = "UAE";
    case Yemen      = "Yemen";

    public static function locationType()
    {
        $arr = [];
        $arr[] = Self::Garage;
        $arr[] = Self::Salalah;
        $arr[] = Self::Mazyoona;
        $arr[] = Self::Thumrait;
        $arr[] = Self::Haila;
        $arr[] = Self::Doka;
        $arr[] = Self::Muqshin;
        $arr[] = Self::Ghaftain;
        $arr[] = Self::Haima;
        $arr[] = Self::Ghaba;
        $arr[] = Self::Adam;
        $arr[] = Self::Nizwa;
        $arr[] = Self::Muscat;
        $arr[] = Self::UAE;
        $arr[] = Self::Yemen;
        return $arr;
    }
}

