<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    //-------------------- Backup Databse -------------\\

    public function GetBackup(Request $request)
    {
        $data = [];
        $id = 0;

        foreach (glob(storage_path().'/app/public/backup/*') as $filename) {
            $item['id'] = $id += 1;
            $item['date'] = basename($filename);
            $size = $this->formatSizeUnits(filesize($filename));
            $item['size'] = $size;

            $data[] = $item;
        }
        $totalRows = count($data);
    }

    //-------------------- Generate Databse -------------\\

    public function GenerateBackup()
    {
        Artisan::call('backup:run');

        return response()->json('Generate complete success');
    }

    //-------------------- Delete Databse -------------\\

    public function DeleteBackup(Request $request, $name)
    {
        foreach (glob(storage_path().'/app/public/backup/*') as $filename) {
            $path = storage_path().'/app/public/backup/'.basename($name);

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

      //-------------------- Fomrmat units -------------\\

      public function formatSizeUnits($bytes)
      {
          if ($bytes >= 1073741824) {
              $bytes = number_format($bytes / 1073741824, 2).' GB';
          } elseif ($bytes >= 1048576) {
              $bytes = number_format($bytes / 1048576, 2).' MB';
          } elseif ($bytes >= 1024) {
              $bytes = number_format($bytes / 1024, 2).' KB';
          } elseif ($bytes > 1) {
              $bytes = $bytes.' bytes';
          } elseif ($bytes == 1) {
              $bytes = $bytes.' byte';
          } else {
              $bytes = '0 bytes';
          }

          return $bytes;
      }
}
