<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Backup;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Backup::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (config('backup.schedule') === 1) {
            $schedule->command(Backup::class)->dailyAt('17:00');
        } elseif (config('backup.schedule') === 2) {
            $schedule->command(Backup::class)->weeklyOn(6, '17:00');
        } elseif (config('backup.schedule') === 3) {
            $schedule->command(Backup::class)->monthly();
        }
       
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
