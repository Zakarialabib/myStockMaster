<?php

declare(strict_types=1);

namespace App\Http\Livewire\Backup;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Index extends Component
{
    public $data = [];

    public function render(): View|Factory
    {
        abort_if(Gate::denies('access_backup'), 403);

        foreach (glob(storage_path().'/public/backup/*') as $filename) {
            $item['id'] = $id += 1;
            $item['date'] = basename($filename);
            $size = $this->formatSizeUnits(filesize($filename));
            $item['size'] = $size;

            $data[] = $item;
        }

        return view('livewire.admin.backup', compact('data'))
    }

    public function generate()
    {
        Artisan::call('backup:run');

        $this->alert('success', __('Backup Generated with success.'));
    }

    public function delete($name)
    {
        foreach (glob(storage_path().'/app/public/backup/*') as $filename) {
            $path = storage_path().'/app/public/backup/'.basename($name);

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

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