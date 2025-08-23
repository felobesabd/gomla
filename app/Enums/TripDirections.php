<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum TripDirections: string
{
    use BaseEnumTrait;

    case MCT_SSL = 'MCT/SSL';
    case SSL_MCT = 'SSL/MCT';
//    case SSL_SWN = 'SSL/SWN';
//    case SWN_SSL = 'SWN/SSL';
    case SSL_YEMEN = 'SSL/YEMEN';
    case YEMEN_SSL = 'YEMEN/SSL';
    case SSL_DXB = 'SSL/DXB';
    case DXB_SSL = 'DXB/SSL';

    public static function tripDirection()
    {
        $arr = [];
        $arr[] = Self::MCT_SSL;
        $arr[] = Self::SSL_MCT;
        $arr[] = Self::SSL_YEMEN;
        $arr[] = Self::YEMEN_SSL;
        $arr[] = Self::SSL_DXB;
        $arr[] = Self::DXB_SSL;
        return $arr;
    }
}

