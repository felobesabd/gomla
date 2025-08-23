<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Get the AliasLoader instance
        $loader = AliasLoader::getInstance();

        // Add your aliases
        $loader->alias('StatusEnum', \App\Enums\Status::class);
        $loader->alias('CountryManufactureEnum', \App\Enums\CountryManufacture::class);
        $loader->alias('TripDirectionEnum', \App\Enums\TripDirections::class);
        $loader->alias('VehicleInsuranceEnum', \App\Enums\Vehicle::class);
        $loader->alias('VehicleStatusEnum', \App\Enums\VehicleStatus::class);
        $loader->alias('VehiclePlateAndLogoEnum', \App\Enums\VehiclePlateLogo::class);
        $loader->alias('VehicleEmissionLevelEnum', \App\Enums\VehicleEmissionLevel::class);
        $loader->alias('JobCardMTypeEnum', \App\Enums\JobCardMType::class);
        $loader->alias('StationStopEnum', \App\Enums\StationStopType::class);
        $loader->alias('StationEnum', \App\Enums\StationType::class);
        $loader->alias('EmployeeTypes', \App\Enums\EmployeeTypes::class);
        $loader->alias('BloodType', \App\Enums\BloodType::class);
        $loader->alias('LicenseGradeType', \App\Enums\LicenseGradeType::class);
        $loader->alias('NotificationFilter', \App\Enums\NotificationFilter::class);
        $loader->alias('Locations', \App\Enums\Locations::class);
        $loader->alias('Supervisors', \App\Enums\Supervisors::class);
        $loader->alias('Coordinators', \App\Enums\OperationCoordinators::class);
        $loader->alias('Brands', \App\Enums\Brands::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
