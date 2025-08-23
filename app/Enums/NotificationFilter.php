<?php

namespace App\Enums;

use App\Traits\BaseEnumTrait;

enum NotificationFilter: string
{
    use BaseEnumTrait;
    case Driving_license_of_employee = "driving license of employee";
    case Passport_of_employee = "passport of employee";
    case Civil_card_of_employee = "civil card of employee";
    case Bank_expired_at_of_employee = "bank expired at of employee";
    case Insurance_medical_of_employee = "insurance medical of employee";
    case Insurance_life_of_employee = "insurance life of employee";

    case Driving_license_of_driver = "driving license of driver";
    case ID_number_valid_of_driver = "id number valid of driver";

    case Expiry_license_date_mot = "expiry license date mot";
    case Plate_number_1_condition = "plate number #1 condition";
    case Plate_number_2_condition = "plate number #2 condition";
    case Logo_stickers_status = "logo stickers status";
    case Vehicle_license = "vehicle license";
    case Fire_cylinder = "fire cylinder";
    case Front_windshield_condition = "front windshield";
    case Second_windshield_condition = "second windshield";

    public static function notificationFilter()
    {
        $arr = [];
        $arr[] = Self::Driving_license_of_employee;
        $arr[] = Self::Passport_of_employee;
        $arr[] = Self::Civil_card_of_employee;
        $arr[] = Self::Bank_expired_at_of_employee;
        $arr[] = Self::Insurance_medical_of_employee;
        $arr[] = Self::Insurance_life_of_employee;
        $arr[] = Self::Driving_license_of_driver;
        $arr[] = Self::ID_number_valid_of_driver;
        $arr[] = Self::Expiry_license_date_mot;
        $arr[] = Self::Plate_number_1_condition;
        $arr[] = Self::Plate_number_2_condition;
        $arr[] = Self::Logo_stickers_status;
        $arr[] = Self::Vehicle_license;
        $arr[] = Self::Fire_cylinder;
        $arr[] = Self::Front_windshield_condition;
        $arr[] = Self::Second_windshield_condition;
        return $arr;
    }
}

