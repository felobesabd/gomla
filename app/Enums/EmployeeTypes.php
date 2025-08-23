<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum EmployeeTypes: string
{
    use BaseEnumTrait;

    case Workshop          = 'workshop';
    case Deputy_Workshop   = 'deputy_workshop';
    case Warehouses        = 'warehouses';
    case Deputy_Warehouses = 'deputy_warehouses';
    case Mechanical        = 'mechanics';
    case Deputy_Mechanical = 'deputy_mechanics';
    case Electrical        = 'electrical_air_conditioning';
    case Deputy_Electrical = 'deputy_electrical_air_conditioning';
    case Plumbing          = 'plumbing';
    case Deputy_Plumbing   = 'deputy_plumbing';
    case Dyeing            = 'dyeing';
    case Deputy_Dyeing     = 'deputy_dyeing';
    case Service           = 'service';
    case Deputy_Service    = 'deputy_service';

    public static function employeeTypes()
    {
        $arr = [];
        $arr[] = Self::Workshop;
        $arr[] = Self::Deputy_Workshop;
        $arr[] = Self::Warehouses;
        $arr[] = Self::Deputy_Warehouses;
        $arr[] = Self::Mechanical;
        $arr[] = Self::Deputy_Mechanical;
        $arr[] = Self::Electrical;
        $arr[] = Self::Deputy_Electrical;
        $arr[] = Self::Plumbing;
        $arr[] = Self::Deputy_Plumbing;
        $arr[] = Self::Dyeing;
        $arr[] = Self::Deputy_Dyeing;
        $arr[] = Self::Service;
        $arr[] = Self::Deputy_Service;
        return $arr;
    }
}

