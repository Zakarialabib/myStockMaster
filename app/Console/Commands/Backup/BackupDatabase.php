<?php

declare(strict_types=1);

namespace App\Console\Commands\Backup;

use Carbon\Carbon;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    const LOCAL_BACKUP_DIR = '/backup/dump';

    const FTP_SERVER = "";

    const  FTP_USER = "";

    const FTP_PASSWORD = "";

    const REMOTE_BACKUP_DIR = "/backup/dumps";

    const DUMP_FILES_COUNT = 4;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup dump of database';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $dumpFileName = $this->dumpDatabase();

        $this->putDumpToBackupServer($dumpFileName);

        $this->deleteTemporaryDumpFile($dumpFileName);

        $this->deleteExpectedDumpFiles();
    }

    /**
     * @return string
     */
    private function dumpDatabase(): string
    {
        $dumpFileName = date('Y-m-d', time()) . '.sql' . '.gz';

        $dumpFilePath = self::LOCAL_BACKUP_DIR . '/' . $dumpFileName;

            $command = '$(which mysqldump) --add-drop-table --allow-keywords -q -c -u ' . config('database.connections.mysql.username') . ' -h ' . config('database.connections.mysql.host') . ' -p' . config('database.connections.mysql.password') . ' ' . config('database.connections.mysql.database') . ' | $(which gzip) > ' . $dumpFilePath;

        exec($command);

        return $dumpFileName;
    }

    /**
     *  Put dump file to remote server.
     * @param string $dumpFileName
     */
    private function putDumpToBackupServer(string $dumpFileName)
    {
        $command = '$(which ftp) -n ' . self::FTP_SERVER . ' <<END_SCRIPT
quote USER ' . self::FTP_USER . '
quote PASS ' . self::FTP_PASSWORD . '
cd ' . self::REMOTE_BACKUP_DIR . '
lcd ' . self::LOCAL_BACKUP_DIR . '
prompt
put ' . $dumpFileName . '
quit
END_SCRIPT';

        exec($command);
    }

    /**
     * Delete temporary local dump file.
     *
     * @param string $dumpFileName
     */
    private function deleteTemporaryDumpFile(string $dumpFileName)
    {
        $dumpFilePath = self::LOCAL_BACKUP_DIR . '/' . $dumpFileName;

        $command = 'rm ' . $dumpFilePath;

        exec($command);
    }

    /**
     * Delete expected remote dump files.
     */
    private function deleteExpectedDumpFiles()
    {
        $command = '$(which ftp) -n ' . self::FTP_SERVER . ' <<END_SCRIPT
quote USER ' . self::FTP_USER . '
quote PASS ' . self::FTP_PASSWORD . '
cd ' . self::REMOTE_BACKUP_DIR . '
nlist
quit
END_SCRIPT';

        $output = [];

        exec($command, $output);

        foreach ($output as $dumpFile){
            $dumpFileParts = explode('.', $dumpFile);
            $dumpFileDate = $dumpFileParts[0];
            $dumpFileCreated = Carbon::createFromFormat('Y-m-d', $dumpFileDate);
            $dumpFileDays = Carbon::today()->diffInDays($dumpFileCreated);

            if ($dumpFileDays >= self::DUMP_FILES_COUNT){
                $command = '$(which ftp) -n ' . self::FTP_SERVER . ' <<END_SCRIPT
quote USER ' . self::FTP_USER . '
quote PASS ' . self::FTP_PASSWORD . '
cd ' . self::REMOTE_BACKUP_DIR . '
delete ' . $dumpFile . '
quit
END_SCRIPT';

                exec($command);
            }
        }
    }
}