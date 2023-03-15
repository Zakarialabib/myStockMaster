<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Backup extends Command
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
        if (config('backup.status') === 1) {
            $artisan_command = '';

            switch (config('backup.content')) {
                case 'db': {
                    $artisan_command = 'backup:run & --only-db';
                    break;
                }
                case 'db_storage': {
                    config(['backup.source.files.include' => 'storage/public']);
                    $artisan_command = 'backup:run';
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
                    \Artisan::call(trim($command[0]), [trim($command[1]) => true]);
                } else {
                    \Artisan::call(array_first($command));
                }
                Log::info('Backup completed successfully!');
            } catch (\Exception $e) {
                \Log::info('backup update failed - ' . $e->getMessage());
            }
        }
    }
}
