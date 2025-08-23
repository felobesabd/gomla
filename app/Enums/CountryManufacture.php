<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum CountryManufacture: string
{
    use BaseEnumTrait;

    case China = 'china';
    case Germany = 'germany';
    case Italy = 'italy';
    case USA = 'usa';
    case Europe = 'europe';
    case Turkey = 'turkey';
    case India = 'india';
    case UAE = 'UAE';

    public static function countryManufacture()
    {
        $arr = [];
        $arr[] = Self::China;
        $arr[] = Self::Germany;
        $arr[] = Self::Italy;
        $arr[] = Self::USA;
        $arr[] = Self::Europe;
        $arr[] = Self::Turkey;
        $arr[] = Self::India;
        $arr[] = Self::UAE;
        return $arr;
    }
}

