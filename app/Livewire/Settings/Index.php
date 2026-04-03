<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Livewire\Utils\WithModels;
use App\Models\Setting;
use App\Traits\WithAlert;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Lazy]
class Index extends Component
{
    use WithAlert;
    use WithFileUploads;
    use WithModels;

    #[Locked]
    public Setting $settings;

    public $invoice_header;

    public $invoice_footer;

    public ?string $invoice_template = null;

    public $image;

    public $site_logo;

    public ?string $site_title = null;

    public ?string $social_facebook = null;

    public ?string $social_twitter = null;

    public ?string $social_instagram = null;

    public ?string $social_linkedin = null;

    public ?string $social_whatsapp = null;

    public ?string $social_tiktok = null;

    public ?string $site_favicon = null;

    #[Validate('required|string|min:1|max:255')]
    public string $company_name;

    #[Validate('required|string|min:1|max:255')]
    public string $company_email;

    #[Validate('required|string|min:1|max:255')]
    public string $company_phone;

    #[Validate('required|string|min:1|max:255')]
    public string $company_address;

    #[Validate('nullable|string|min:0|max:255')]
    public ?string $company_tax = null;

    #[Validate('nullable|string|min:0|max:255')]
    public ?string $telegram_channel = null;

    #[Validate('required|integer|min:0|max:192')]
    public int $default_currency_id;

    #[Validate('required|string|min:1|max:255')]
    public string $default_currency_position;

    #[Validate('required|string|min:1|max:255')]
    public string $default_date_format;

    public ?int $default_client_id = null;

    public ?int $default_warehouse_id = null;

    // #[Validate('boolean')]
    // public $multi_language;

    public ?string $invoice_footer_text = null;

    public ?string $sale_prefix = null;

    public ?string $saleReturn_prefix = null;

    public ?string $purchase_prefix = null;

    public ?string $purchaseReturn_prefix = null;

    public ?string $quotation_prefix = null;

    public ?string $salePayment_prefix = null;

    public ?string $purchasePayment_prefix = null;

    public ?string $expense_prefix = null;

    public ?string $delivery_prefix = null;

    public bool $is_rtl = false;

    public bool $show_email = false;

    public bool $show_address = false;

    public bool $show_order_tax = false;

    public bool $show_discount = false;

    public bool $show_shipping = false;

    public ?string $head_tags = null;

    public ?string $body_tags = null;

    public ?string $seo_meta_title = null;

    public ?string $seo_meta_description = null;

    public ?string $whatsapp_custom_message = null;

    public function render()
    {
        return view('livewire.settings.index');
    }

    public ?array $analyticsControl = null;

    public array $colors = ['blue', 'orange', 'green', 'indigo', 'teal', 'cyan', 'yellow', 'purple', 'red'];

    public ?array $invoice_control = null;

    public function save()
    {
        // Save updated analytics control settings
        // $updatedAnalyticsControl = json_encode($this->analyticsControl);

        // Example: save to database or session
        // Example: emit event to update parent component
        // $this->dispatch('analyticsControlUpdated', $updatedAnalyticsControl);
    }

    public function toggleStatus($index): void
    {
        $this->analyticsControl[$index]['status'] = ! $this->analyticsControl[$index]['status'];
        $this->settings->save();
    }

    public function changeColor($index, $color): void
    {
        $this->analyticsControl[$index]['color'] = $color;
        $this->settings->save();
    }

    public function updatedInvoiceControl($field)
    {
        // Update settings when checkboxes are toggled
        foreach ($this->invoice_control as $index => $control) {
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

        $this->settings = Setting::firstOrFail();

        $this->site_logo = $this->settings->site_logo;
        $this->site_title = $this->settings->site_title;
        $this->site_favicon = $this->settings->site_favicon;
        $this->company_name = $this->settings->company_name;
        $this->company_email = $this->settings->company_email;
        $this->company_phone = $this->settings->company_phone;
        $this->company_address = $this->settings->company_address;
        $this->company_tax = $this->settings->company_tax;
        $this->telegram_channel = $this->settings->telegram_channel;
        $this->default_currency_id = $this->settings->default_currency_id;
        $this->default_currency_position = $this->settings->default_currency_position;
        $this->default_date_format = $this->settings->default_date_format;
        $this->default_client_id = $this->settings->default_client_id;
        $this->default_warehouse_id = $this->settings->default_warehouse_id;
        $this->invoice_footer_text = $this->settings->invoice_footer_text;
        $this->sale_prefix = $this->settings->sale_prefix;
        $this->saleReturn_prefix = $this->settings->saleReturn_prefix;
        $this->purchase_prefix = $this->settings->purchase_prefix;
        $this->purchaseReturn_prefix = $this->settings->purchaseReturn_prefix;
        $this->quotation_prefix = $this->settings->quotation_prefix;
        $this->salePayment_prefix = $this->settings->salePayment_prefix;
        $this->purchasePayment_prefix = $this->settings->purchasePayment_prefix;
        $this->expense_prefix = $this->settings->expense_prefix;
        $this->delivery_prefix = $this->settings->delivery_prefix;
        $this->is_rtl = (bool) $this->settings->is_rtl;

        $this->social_facebook = $this->settings->social_facebook;
        $this->social_twitter = $this->settings->social_twitter;
        $this->social_instagram = $this->settings->social_instagram;
        $this->social_linkedin = $this->settings->social_linkedin;
        $this->social_whatsapp = $this->settings->social_whatsapp;
        $this->social_tiktok = $this->settings->social_tiktok;
        $this->head_tags = $this->settings->head_tags;
        $this->body_tags = $this->settings->body_tags;
        $this->seo_meta_title = $this->settings->seo_meta_title;
        $this->seo_meta_description = $this->settings->seo_meta_description;
        $this->whatsapp_custom_message = $this->settings->whatsapp_custom_message;
        $this->invoice_template = $this->settings->invoice_template;
        $this->invoice_control = $this->settings->invoice_control;
        $this->analyticsControl = $this->settings->analytics_control;
    }

    public function saveImage()
    {
        // Handle file uploads
        if ($this->invoice_header) {
            $imageName = 'invoice-header';
            $this->storeImage($this->invoice_header, $imageName);
            $this->settings->invoice_header = $imageName;
        }

        if ($this->invoice_footer) {
            $imageName = 'invoice-footer';
            $this->storeImage($this->invoice_footer, $imageName);
            $this->settings->invoice_footer = $imageName;
        }

        if ($this->site_logo) {
            $imageName = 'logo';
            $this->storeImage($this->site_logo, $imageName);
            $this->settings->site_logo = $imageName;
        }

        if ($this->site_favicon) {
            $imageName = 'favicon';
            $this->storeImage($this->site_favicon, $imageName);
            $this->settings->site_favicon = $imageName;
        }
    }

    #[On('update')]
    public function update(): void
    {
        $this->validate();

        if ($this->invoice_header) {
            $imageName = 'invoice-header';
            Storage::put('invoice', $imageName, 'local_files');
            $this->createHTMLfile($this->invoice_header, $imageName);
            $this->invoice_header = $imageName;
        }

        if ($this->invoice_footer) {
            $imageName = 'invoice-footer';
            Storage::put('invoice', $imageName, 'local_files');
            $this->createHTMLfile($this->invoice_footer, $imageName);
            $this->invoice_footer = $imageName;
        }

        if (! $this->site_logo) {
            $imageName = 'logo';
            $this->site_logo->storeAs('images', $imageName, 'local_files');
            $this->site_logo = $imageName;
        }

        if ($this->site_favicon) {
            $imageName = 'favicon';
            $this->site_favicon->storeAs('images', $imageName, 'local_files');
            $this->site_favicon = $imageName;
        }

        $this->settings->update([
            'site_logo' => $this->site_logo,
            'site_title' => $this->site_title,
            'site_favicon' => $this->site_favicon,
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => $this->company_phone,
            'company_address' => $this->company_address,
            'company_tax' => $this->company_tax,
            'telegram_channel' => $this->telegram_channel,
            'default_currency_id' => $this->default_currency_id,
            'default_currency_position' => $this->default_currency_position,
            'default_date_format' => $this->default_date_format,
            'default_client_id' => $this->default_client_id,
            'default_warehouse_id' => $this->default_warehouse_id,
            // 'multi_language'            => $this->multi_language,
            'invoice_footer_text' => $this->invoice_footer_text,
            'sale_prefix' => $this->sale_prefix,
            'saleReturn_prefix' => $this->saleReturn_prefix,
            'purchase_prefix' => $this->purchase_prefix,
            'purchaseReturn_prefix' => $this->purchaseReturn_prefix,
            'quotation_prefix' => $this->quotation_prefix,
            'salePayment_prefix' => $this->salePayment_prefix,
            'purchasePayment_prefix' => $this->purchasePayment_prefix,
            'expense_prefix' => $this->expense_prefix,
            'delivery_prefix' => $this->delivery_prefix,
            'is_rtl' => $this->is_rtl,
            'show_email' => $this->show_email,
            'show_address' => $this->show_address,
            'show_order_tax' => $this->show_order_tax,
            'show_discount' => $this->show_discount,
            'show_shipping' => $this->show_shipping,
            'social_facebook' => $this->social_facebook,
            'social_twitter' => $this->social_twitter,
            'social_instagram' => $this->social_instagram,
            'social_linkedin' => $this->social_linkedin,
            'social_whatsapp' => $this->social_whatsapp,
            'social_tiktok' => $this->social_tiktok,
            'head_tags' => $this->head_tags,
            'body_tags' => $this->body_tags,
            'seo_meta_title' => $this->seo_meta_title,
            'seo_meta_description' => $this->seo_meta_description,
            'whatsapp_custom_message' => $this->whatsapp_custom_message,
            'invoice_template' => $this->invoice_template,
        ]);

        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));
    }

    protected function createHTMLfile($file, string $name): string
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
