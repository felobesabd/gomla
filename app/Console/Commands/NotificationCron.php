<?php

namespace App\Console\Commands;

use App\Enums\NotificationTypes;
use App\Models\Driver;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class NotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-notifications:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Notification in background';

    /**
     * Execute the console command.
     */
    public function handle()
    {


    }



}
