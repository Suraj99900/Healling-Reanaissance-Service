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
        // Nightly pending video conversion queueing (every 30 mins between 10 PM and 6 AM)
        $schedule->command('convert:pending-videos')->cron('*/30 22-23,0-5 * * *')->withoutOverlapping();

        // Daytime HLS status sync (every 15 mins starting at 6 AM until 10 PM)
        $schedule->command('video:update-hls-status')->cron('*/15 6-21 * * *');
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
