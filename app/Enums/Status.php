<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum Status: int
{
    use BaseEnumTrait;

    case Pending = 0;
    case Completed = 1;
    case Cancelled = 2;
    case Parts_Received = 3;
    case Testing = 4;
    case Active = 5;
    case Inactive = 6;

    public static function jobCardCasesForWarehouseManager() {
        $arr = [];
        $arr[] = Self::Pending;
        $arr[] = Self::Parts_Received;
        return $arr;
    }

    public static function jobCardCasesForAdmin() {
        $arr = [];
        $arr[] = Self::Testing;
        $arr[] = Self::Completed;
        $arr[] = Self::Cancelled;
        return $arr;
    }

    public static function jobCardCases() {
        $arr = [];
        $arr[] = Self::Pending;
        $arr[] = Self::Testing;
        $arr[] = Self::Parts_Received;
        $arr[] = Self::Completed;
        $arr[] = Self::Cancelled;
        return $arr;
    }
}
