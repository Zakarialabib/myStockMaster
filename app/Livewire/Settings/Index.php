<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Livewire\Forms\SettingForm;
use App\Livewire\Utils\WithModels;
use App\Models\Setting;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Settings')]
class Index extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    public SettingForm $form;

    #[Locked]
    public Setting $settings;

    public ?array $analyticsControl = null;

    public array $colors = ['blue', 'orange', 'green', 'indigo', 'teal', 'cyan', 'yellow', 'purple', 'red'];

    public ?array $invoice_control = null;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.settings.index');
    }

    public function save(): void
    {
        // Save updated analytics control settings
        // $updatedAnalyticsControl = json_encode($this->analyticsControl);

        // Example: save to database or session
        // Example: emit event to update parent component
        // $this->dispatch('analyticsControlUpdated', $updatedAnalyticsControl);
    }

    public function toggleStatus(mixed $index): void
    {
        $this->analyticsControl[$index]['status'] = ! $this->analyticsControl[$index]['status'];
        $this->settings->analytics_control = $this->analyticsControl;
        $this->settings->save();
    }

    public function changeColor(mixed $index, mixed $color): void
    {
        $this->analyticsControl[$index]['color'] = $color;
        $this->settings->analytics_control = $this->analyticsControl;
        $this->settings->save();
    }

    public function updatedInvoiceControl(mixed $field): void
    {
        foreach ($this->invoice_control as $index => $control) {
            if ($control['name'] === $field) {
                // The binding already updates $this->invoice_control
                $this->settings->invoice_control = $this->invoice_control;
                $this->settings->save();
                break;
            }
        }

        $this->alert('success', __('Settings Updated successfully!'));
    }

    public function mount(): void
    {
        abort_if(Gate::denies('setting_access'), 403);

        $this->settings = Setting::query()->firstOrFail();
        $this->form->setSetting($this->settings);

        $this->invoice_control = $this->settings->invoice_control ?? [];
        $this->analyticsControl = $this->settings->analytics_control ?? [];
    }

    public function saveImage(): void
    {
        // Handle file uploads
        if ($this->form->invoice_header) {
            $imageName = 'invoice-header';
            $this->storeImage($this->form->invoice_header, $imageName);
            $this->settings->invoice_header = $imageName;
        }

        if ($this->form->invoice_footer) {
            $imageName = 'invoice-footer';
            $this->storeImage($this->form->invoice_footer, $imageName);
            $this->settings->invoice_footer = $imageName;
        }

        if ($this->form->site_logo) {
            $imageName = 'logo';
            $this->storeImage($this->form->site_logo, $imageName);
            $this->settings->site_logo = $imageName;
        }

        if ($this->form->site_favicon) {
            $imageName = 'favicon';
            $this->storeImage($this->form->site_favicon, $imageName);
            $this->settings->site_favicon = $imageName;
        }
    }

    private function storeImage(mixed $image, string $name): void
    {
        $image->storeAs('settings', $name, 'public');
    }

    #[On('update')]
    public function update(): void
    {
        $this->form->validate();

        if ($this->form->invoice_header && ! is_string($this->form->invoice_header)) {
            $imageName = 'invoice-header';
            $this->form->invoice_header->storeAs('settings', $imageName, 'public');
            $this->form->invoice_header = $imageName;
        }

        if ($this->form->invoice_footer && ! is_string($this->form->invoice_footer)) {
            $imageName = 'invoice-footer';
            $this->form->invoice_footer->storeAs('settings', $imageName, 'public');
            $this->form->invoice_footer = $imageName;
        }

        if ($this->form->site_logo && ! is_string($this->form->site_logo)) {
            $imageName = 'logo';
            $this->form->site_logo->storeAs('images', $imageName, 'local_files');
            $this->form->site_logo = $imageName;
        }

        if ($this->form->site_favicon && ! is_string($this->form->site_favicon)) {
            $imageName = 'favicon';
            $this->form->site_favicon->storeAs('images', $imageName, 'local_files');
            $this->form->site_favicon = $imageName;
        }

        $this->form->update();

        if ($this->form->site_logo && is_string($this->form->site_logo)) {
            $this->settings->site_logo = $this->form->site_logo;
        }

        if ($this->form->site_favicon && is_string($this->form->site_favicon)) {
            $this->settings->site_favicon = $this->form->site_favicon;
        }

        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));

        $this->dispatch('settings-saved');
    }
}
