<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Livewire\Forms\SettingsForm;
use App\Livewire\Utils\WithModels;
use App\Models\Setting;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithAlert;
    use WithModels;

    public SettingsForm $form;

    #[Locked]
    public Setting $settings;

    public function mount(): void
    {
        abort_if(Gate::denies('setting_access'), 403);

        $this->settings = Setting::firstOrFail();
        $this->form->setSetting($this->settings);
    }

    public function update(): void
    {
        $this->form->update();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully!'));
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
