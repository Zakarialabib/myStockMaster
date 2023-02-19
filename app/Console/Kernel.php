<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\Backup\BackupDatabase;
use App\Console\Commands\Backup\BackupFiles;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        BackupDatabase::class,
        BackupFiles::class,
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
        // some config somewhere

        if (config('backup.schedule') === 1) {
            $schedule->command(BackupDatabase::class)->dailyAt('17:00');
            $schedule->command(BackupFiles::class)->dailyAt('17:00');
        } elseif (config('backup.schedule') === 2) {
            $schedule->command(BackupDatabase::class)->weeklyOn(6, '17:00');
            $schedule->command(BackupFiles::class)
                ->weeklyOn(6, '17:00');
        } elseif (config('backup.schedule') === 3) {
            $schedule->command(BackupDatabase::class)->monthly();
            $schedule->command(BackupFiles::class)->monthly();
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
