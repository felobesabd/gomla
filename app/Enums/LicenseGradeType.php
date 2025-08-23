<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum LicenseGradeType: string
{
    use BaseEnumTrait;

    case Light = "light";
    case Heavy = "heavy";
    case PDO   = "pdo";

    public static function licenseGradeType()
    {
        $arr = [];
        $arr[] = Self::Light;
        $arr[] = Self::Heavy;
        $arr[] = Self::PDO;
        return $arr;
    }
}

