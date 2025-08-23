<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpiryDates extends Command
{
    protected $signature = 'app:check-expiry-dates';
    protected $description = 'Check for passports and driving licenses that are about to expire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sixMonthsFromNow = Carbon::now()->addMonths(6);

        $threeMonthsFromNow = Carbon::now()->addMonths(3);

        $expiringPassports = Employee::whereDate('passport_expires_at', '<=', $sixMonthsFromNow)
                                    ->whereNotNull('passport_expires_at')
                                    ->get();

        foreach ($expiringPassports as $employee) {
            $this->info('Passport expiring for: ' . $employee->name);
        }

        $expiringLicenses = Employee::whereDate('driving_license_expires_at', '<=', $threeMonthsFromNow)
                                    ->whereNotNull('driving_license_expires_at')
                                    ->get();

        foreach ($expiringLicenses as $employee) {
            $this->info('Driving license expiring for: ' . $employee->name);
        }

        return 0;
    }
}
