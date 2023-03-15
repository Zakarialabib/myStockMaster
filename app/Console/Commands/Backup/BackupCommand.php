<?php

declare(strict_types=1);

namespace App\Console\Commands\Backup;

use Artisan;
use Exception;
use Illuminate\Console\Command;
use Log;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate backups of the system according to specified in configs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (settings()->backup_status === 1) {
            $artisan_command = '';

            switch (settings()->backup_content) {
                case 'db': {
                    $artisan_command = 'backup:run & --only-db';

                    break;
                }
                case 'all': {
                    config(['backup.source.files.include' => base_path()]);
                    $artisan_command = 'backup:run';

                    break;
                }
            }

            $command = explode('&', $artisan_command);

            try {
                if (count($command) > 1) {
                    Artisan::call(trim($command[0]), [trim($command[1]) => true]);
                } else {
                    Artisan::call(array_first($command));
                }
                Log::info('Backup completed successfully!');
            } catch (Exception $e) {
                Log::info('backup update failed - '.$e->getMessage());
            }
        }
    }
}
