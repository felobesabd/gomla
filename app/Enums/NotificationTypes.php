<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum NotificationTypes: int
{
    use BaseEnumTrait;

    case warning_expired_passport = 0;
    case warning_expired_driving_license = 1;
    case warning_expired_stay = 2;
    case warning_expired_vehicle_license = 3;
    case warning_expired_fire_cylinder = 4;
    case warning_expired_insurance_medical = 5;
    case warning_item_qty_less_than_min_allowed_value = 6;
    case warning_civil_card_end_date = 7;
    case warning_item_max_distance_less_than_vehicle_estimated_km = 8;
    case warning_bank_expired_at = 9;
    case warning_id_num_valid = 10;
    case warning_expiry_license_date_mot = 11;
    case warning_part_of_vehicle_need_replacement = 12;
    case warning_expired_fire_cylinder_valid_2 = 13;
    public static function notificationTypes() :array
    {
        $arr = [];
        $arr[] = self::warning_expired_passport;
        $arr[] = self::warning_expired_driving_license;
        $arr[] = self::warning_expired_stay;
        $arr[] = self::warning_expired_vehicle_license;
        $arr[] = self::warning_expired_fire_cylinder;
        $arr[] = self::warning_item_qty_less_than_min_allowed_value;
        $arr[] = self::warning_item_max_distance_less_than_vehicle_estimated_km;
        $arr[] = self::warning_bank_expired_at;
        $arr[] = self::warning_id_num_valid;
        $arr[] = self::warning_expiry_license_date_mot;
        $arr[] = self::warning_part_of_vehicle_need_replacement;
        $arr[] = self::warning_expired_fire_cylinder_valid_2;
        return $arr;
    }
}

