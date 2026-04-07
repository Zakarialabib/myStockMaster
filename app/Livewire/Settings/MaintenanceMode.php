<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Jobs\UnderMaintenanceJob;
use App\Models\Setting;
use App\Traits\WithAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Livewire\Component;

class MaintenanceMode extends Component
{
    use WithAlert;

    public ?string $site_maintenance_message = null;

    public ?bool $status = null;

    public ?string $secret = null;

    public ?int $refresh = null;

    public function mount(): void
    {
        $settings = settings();

        $this->site_maintenance_message = $settings->site_maintenance_message;
        $this->status = $settings->site_maintenance_status;
        $this->refresh = $settings->site_refresh;
        $this->secret = Str::uuid()->toString();
    }

    public function saveSettings(): void
    {
        $this->validate([
            'site_maintenance_message' => 'required',
            // 'site_maintenance_status' => 'required',
        ]);

        Setting::set('site_maintenance_message', $this->site_maintenance_message);
        Setting::set('site_refresh', $this->refresh);

        $this->alert('success', __('Settings saved successfully.'));
    }

    public function turnOff(): RedirectResponse
    {
        Setting::set('site_maintenance_status', false);

        Setting::set('site_maintenance_secret', $this->secret);

        dispatch(new \App\Jobs\UnderMaintenanceJob($this->secret, $this->refresh));

        $this->alert('success', implode(' ', ['status' => $this->status ? __('System turned on') : __('System turned off')]));

        return to_route('front.index', ['secret' => $this->secret]);
        // Send email notification
        // Mail::to($user)->send(new MaintenanceModeNotification(false));
    }

    public function turnOn(): void
    {
        Artisan::call('up');

        Setting::set('site_maintenance_status', true);

        $this->alert('success', __('System turned on.'));

        // $user = auth()->user()->email;
        // Send email notification
        // Mail::to($user)->send(new MaintenanceModeNotification(true));
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.settings.maintenance-mode');
    }
}
