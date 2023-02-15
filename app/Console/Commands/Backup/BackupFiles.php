<?php

declare(strict_types=1);

namespace App\Console\Commands\Backup;

use Illuminate\Console\Command;

class BackupFiles extends Command
{
    const LOCAL_BACKUP_DIR = '/mnt/cloudisk/public';

    const FTP_SERVER = "";

    const  FTP_USER = "";

    const FTP_PASSWORD = "";

    const REMOTE_BACKUP_DIR = "/backup/assets";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup files and images from cloudisk';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
//        $this->deleteOldFilesFromBackupServer();
        $this->putFilesToBackupServer();
    }

    /**
     * Delete old files from backup server.
     */
    private function deleteOldFilesFromBackupServer()
    {
        $command = '$(which lftp) -u ' . self::FTP_USER . ',' . self::FTP_PASSWORD . ' ' . self::FTP_SERVER . ' <<END_SCRIPT
cd ' . self::REMOTE_BACKUP_DIR . '
glob -a rm -r ' . self::REMOTE_BACKUP_DIR . '/*
quit
END_SCRIPT';

        exec($command);
    }

    /**
     * Put new files to backup server.
     */
    private function putFilesToBackupServer()
    {
        $command = '$(which ncftp) -u ' . self::FTP_USER . ' -p ' . self::FTP_PASSWORD . ' ' . self::FTP_SERVER . ' <<END_SCRIPT
        
cd ' . self::REMOTE_BACKUP_DIR . '
lcd ' . self::LOCAL_BACKUP_DIR . '
put -P 4 -R *
quit
END_SCRIPT';

        exec($command);
    }
}