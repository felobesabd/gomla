<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum JobCardMType: int
{
    use BaseEnumTrait;

    case Warranty_Maintenance = 1;
    case External_Breakdown_Maintenance = 2;
    case Preventive_Maintenance = 3;
    case Corrective_Maintenance = 4;

    public static function JobCardMaintenanceType()
    {
        $arr = [];
        $arr[] = Self::Warranty_Maintenance;
        $arr[] = Self::External_Breakdown_Maintenance;
        $arr[] = Self::Preventive_Maintenance;
        $arr[] = Self::Corrective_Maintenance;
        return $arr;
    }
}

