<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Helpers\GitHandler;
use App\Traits\WithAlert;
use Livewire\Component;

class Update extends Component
{
    use WithAlert;

    public ?string $message = null;

    public ?bool $updateAvailable = null;

    public function checkForUpdates(): void
    {
        $gitHandler = new GitHandler;
        $updatesAvailable = $gitHandler->checkForUpdates();

        if ($updatesAvailable) {
            $this->updateAvailable = true;
            $this->message = 'Updates available on origin/' . config('app.git_branch', 'master') . '.';
        } else {
            $this->message = 'No updates available.';
        }
    }

    public function updateSystem(): void
    {
        $gitHandler = new GitHandler;
        $this->message = $gitHandler->fetchAndPull();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.settings.update');
    }
}
