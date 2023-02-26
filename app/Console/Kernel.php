<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\Backup\BackupCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        BackupCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if (config('backup.schedule') === 1) {
            $schedule->command(BackupCommand::class)->daily();
        } elseif (config('backup.schedule') === 2) {
            $schedule->command(BackupCommand::class)->weekly();
        } elseif (config('backup.schedule') === 3) {
            $schedule->command(BackupCommand::class)->monthly();
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
