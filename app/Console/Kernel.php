<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check and queue videos uploaded at least 1 hour ago (runs 24/7 every 15 mins)
        $schedule->command('convert:pending-videos')->everyFifteenMinutes()->withoutOverlapping();

        // Daytime HLS status sync (every 15 mins starting at 6 AM until 10 PM)
        $schedule->command('video:update-hls-status')->cron('*/15 6-21 * * *')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
