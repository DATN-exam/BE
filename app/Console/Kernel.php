<?php

namespace App\Console;

use App\Console\Commands\AutoSubmitCron;
use App\Console\Commands\NotificationEndExamCron;
use App\Console\Commands\NotificationStartExamCron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\AutoSubmitCron::class,
        Commands\NotificationStartExamCron::class,
        Commands\NotificationEndExamCron::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(AutoSubmitCron::class)->everyMinute();
        $schedule->command(NotificationStartExamCron::class)->everyMinute();
        $schedule->command(NotificationEndExamCron::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
