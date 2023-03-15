<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\Backup\BackupCommand;
use App\Enums\BackupSchedule;
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
        if (settings()->backup_schedule === BackupSchedule::DAILY) {
            $schedule->command('backup:monitor')->daily()->at('17:00');
            $schedule->command(BackupCommand::class)->daily()->at('17:00');
        } elseif (settings()->backup_schedule === BackupSchedule::WEEKLY) {
            $schedule->command('backup:monitor')->weekly()->at('17:00');
            $schedule->command(BackupCommand::class)->weekly()->at('17:00');
        } elseif (settings()->backup_schedule === BackupSchedule::MONTHLY) {
            $schedule->command('backup:monitor')->monthly()->at('17:00');
            $schedule->command(BackupCommand::class)->monthly()->at('17:00');
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
