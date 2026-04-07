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
        $this->settings->save();
    }

    public function changeColor(mixed $index, mixed $color): void
    {
        $this->analyticsControl[$index]['color'] = $color;
        $this->settings->save();
    }

    public function updatedInvoiceControl(mixed $field): void
    {
        // Update settings when checkboxes are toggled
        foreach ($this->invoice_control as $control) {
            if ($control['name'] === $field) {
                $this->settings->{$field} = $control['status'];
                $this->settings->save();

                break;
            }
        }

        // Optionally add an alert or message for confirmation
        $this->alert('success', __('Settings Updated successfully!'));
    }

    public function mount(): void
    {
        abort_if(Gate::denies('setting_access'), 403);

        $this->settings = Setting::query()->firstOrFail();
        $this->form->setSetting($this->settings);

        $this->invoice_control = is_string($this->settings->invoice_control) ? json_decode($this->settings->invoice_control, true) : $this->settings->invoice_control;
        $this->analyticsControl = is_string($this->settings->analytics_control) ? json_decode($this->settings->analytics_control, true) : $this->settings->analytics_control;
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
            Storage::put('invoice', $imageName, 'local_files');
            $this->createHTMLfile($this->form->invoice_header, $imageName);
            $this->form->invoice_header = $imageName;
        }

        if ($this->form->invoice_footer && ! is_string($this->form->invoice_footer)) {
            $imageName = 'invoice-footer';
            Storage::put('invoice', $imageName, 'local_files');
            $this->createHTMLfile($this->form->invoice_footer, $imageName);
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

    protected function createHTMLfile(mixed $file, string $name): string
    {
        $extension = $file->extension();
        $data = File::get($file->getRealPath());
        $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);

        $html = sprintf(
            '<div><img style="width: 100%%; display: block;" src="%s"></div>',
            $base64
        );

        $path = public_path('print/' . $name . '.html');
        File::put($path, $html);

        return $base64;
    }
}
