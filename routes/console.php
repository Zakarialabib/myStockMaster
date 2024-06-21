<?php

declare(strict_types=1);

use App\Console\Commands\BackupCommand;
use App\Enums\BackupSchedule;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// if (settings()->backup_schedule === BackupSchedule::DAILY) {
//     Schedule::command('backup:monitor')->dailyAt('17:00');
//     Schedule::command(BackupCommand::class)->dailyAt('17:00');
// } elseif (settings()->backup_schedule === BackupSchedule::WEEKLY) {
//     Schedule::command('backup:monitor')->weeklyOn(1, '17:00');
//     Schedule::command(BackupCommand::class)->weeklyOn(1, '17:00');
// } elseif (settings()->backup_schedule === BackupSchedule::MONTHLY) {
//     Schedule::command('backup:monitor')->monthlyOn(1, '17:00');
//     Schedule::command(BackupCommand::class)->monthlyOn(1, '17:00');
// }
