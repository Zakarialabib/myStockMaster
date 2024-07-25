<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Livewire\Utils\WithModels;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    use WithModels;

    public $settings;

    /** @var array<string> */
    public $listeners = ['update'];

    public $invoice_header;

    public $invoice_footer;

    public $invoice_template;

    public $image;

    public $site_logo;

    public $site_title;

    public $social_facebook;

    public $social_twitter;

    public $social_instagram;

    public $social_linkedin;

    public $social_whatsapp;

    public $social_tiktok;

    public $site_favicon;

    #[Validate('required|string|min:1|max:255')]
    public $company_name;

    #[Validate('required|string|min:1|max:255')]
    public $company_email;

    #[Validate('required|string|min:1|max:255')]
    public $company_phone;

    #[Validate('required|string|min:1|max:255')]
    public $company_address;

    #[Validate('nullable|string|min:0|max:255')]
    public $company_tax;

    #[Validate('nullable|string|min:0|max:255')]
    public $telegram_channel;

    #[Validate('required|integer|min:0|max:192')]
    public $default_currency_id;

    #[Validate('required|string|min:1|max:255')]
    public $default_currency_position;

    #[Validate('required|string|min:1|max:255')]
    public $default_date_format;

    public $default_client_id;

    public $default_warehouse_id;

    // #[Validate('boolean')]
    // public $multi_language;

    public $invoice_footer_text;

    public $sale_prefix;

    public $saleReturn_prefix;

    public $purchase_prefix;

    public $purchaseReturn_prefix;

    public $quotation_prefix;

    public $salePayment_prefix;

    public $purchasePayment_prefix;

    public $expense_prefix;

    public $delivery_prefix;

    public $is_rtl;

    public $show_email;

    public $show_address;

    public $show_order_tax;

    public $show_discount;

    public $show_shipping;

    public $head_tags;

    public $body_tags;

    public $seo_meta_title;

    public $seo_meta_description;

    public $whatsapp_custom_message;

    public function render()
    {
        return view('livewire.settings.index');
    }

    public $analyticsControl;
    public $colors = ['blue', 'orange', 'green', 'indigo', 'teal', 'cyan', 'yellow', 'purple', 'red'];
    public $invoice_control;

    public function save()
    {
        // Save updated analytics control settings
        // $updatedAnalyticsControl = json_encode($this->analyticsControl);

        // Example: save to database or session
        // Example: emit event to update parent component
        // $this->emit('analyticsControlUpdated', $updatedAnalyticsControl);
    }

    public function toggleStatus($index)
    {
        $this->settings = Setting::first();

        $this->analyticsControl[$index]['status'] = ! $this->analyticsControl[$index]['status'];
        $this->settings->save();
        // $this->save();
    }

    public function changeColor($index, $color)
    {
        $this->settings = Setting::first();
        $this->analyticsControl[$index]['color'] = $color;
        $this->settings->save();
        // $this->save();
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

        $this->site_logo = settings()->site_logo;
        $this->site_title = settings()->site_title;
        $this->site_favicon = settings()->site_favicon;
        $this->company_name = settings()->company_name;
        $this->company_email = settings()->company_email;
        $this->company_phone = settings()->company_phone;
        $this->company_address = settings()->company_address;
        $this->company_tax = settings()->company_tax;
        $this->telegram_channel = settings()->telegram_channel;
        $this->default_currency_id = settings()->default_currency_id;
        $this->default_currency_position = settings()->default_currency_position;
        $this->default_date_format = settings()->default_date_format;
        $this->default_client_id = settings()->default_client_id;
        $this->default_warehouse_id = settings()->default_warehouse_id;
        // $this->multi_language = settings()->multi_language;
        $this->invoice_footer_text = settings()->invoice_footer_text;
        $this->sale_prefix = settings()->sale_prefix;
        $this->saleReturn_prefix = settings()->saleReturn_prefix;
        $this->purchase_prefix = settings()->purchase_prefix;
        $this->purchaseReturn_prefix = settings()->purchaseReturn_prefix;
        $this->quotation_prefix = settings()->quotation_prefix;
        $this->salePayment_prefix = settings()->salePayment_prefix;
        $this->purchasePayment_prefix = settings()->purchasePayment_prefix;
        $this->expense_prefix = settings()->expense_prefix;
        $this->delivery_prefix = settings()->delivery_prefix;
        $this->is_rtl = settings()->is_rtl;

        $this->social_facebook = settings()->social_facebook;
        $this->social_twitter = settings()->social_twitter;
        $this->social_instagram = settings()->social_instagram;
        $this->social_linkedin = settings()->social_linkedin;
        $this->social_whatsapp = settings()->social_whatsapp;
        $this->social_tiktok = settings()->social_tiktok;
        $this->head_tags = settings()->head_tags;
        $this->body_tags = settings()->body_tags;
        $this->seo_meta_title = settings()->seo_meta_title;
        $this->seo_meta_description = settings()->seo_meta_description;
        $this->whatsapp_custom_message = settings()->whatsapp_custom_message;
        $this->invoice_template = settings()->invoice_template;
        $this->invoice_control = settings()->invoice_control;
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

        if ( ! $this->site_logo) {
            $imageName = 'logo';
            $this->site_logo->storeAs('images', $imageName, 'local_files');
            $this->site_logo = $imageName;
        }

        if ($this->site_favicon) {
            $imageName = 'favicon';
            $this->site_favicon->storeAs('images', $imageName, 'local_files');
            $this->site_favicon = $imageName;
        }

        $this->settings = Setting::firstOrFail(); // Retrieve the existing settings object

        $this->settings->update([
            'site_logo'                 => $this->site_logo,
            'site_title'                => $this->site_title,
            'site_favicon'              => $this->site_favicon,
            'company_name'              => $this->company_name,
            'company_email'             => $this->company_email,
            'company_phone'             => $this->company_phone,
            'company_address'           => $this->company_address,
            'company_tax'               => $this->company_tax,
            'telegram_channel'          => $this->telegram_channel,
            'default_currency_id'       => $this->default_currency_id,
            'default_currency_position' => $this->default_currency_position,
            'default_date_format'       => $this->default_date_format,
            'default_client_id'         => $this->default_client_id,
            'default_warehouse_id'      => $this->default_warehouse_id,
            // 'multi_language'            => $this->multi_language,
            'invoice_footer_text'     => $this->invoice_footer_text,
            'sale_prefix'             => $this->sale_prefix,
            'saleReturn_prefix'       => $this->saleReturn_prefix,
            'purchase_prefix'         => $this->purchase_prefix,
            'purchaseReturn_prefix'   => $this->purchaseReturn_prefix,
            'quotation_prefix'        => $this->quotation_prefix,
            'salePayment_prefix'      => $this->salePayment_prefix,
            'purchasePayment_prefix'  => $this->purchasePayment_prefix,
            'expense_prefix'          => $this->expense_prefix,
            'delivery_prefix'         => $this->delivery_prefix,
            'is_rtl'                  => $this->is_rtl,
            'show_email'              => $this->show_email,
            'show_address'            => $this->show_address,
            'show_order_tax'          => $this->show_order_tax,
            'show_discount'           => $this->show_discount,
            'show_shipping'           => $this->show_shipping,
            'social_facebook'         => $this->social_facebook,
            'social_twitter'          => $this->social_twitter,
            'social_instagram'        => $this->social_instagram,
            'social_linkedin'         => $this->social_linkedin,
            'social_whatsapp'         => $this->social_whatsapp,
            'social_tiktok'           => $this->social_tiktok,
            'head_tags'               => $this->head_tags,
            'body_tags'               => $this->body_tags,
            'seo_meta_title'          => $this->seo_meta_title,
            'seo_meta_description'    => $this->seo_meta_description,
            'whatsapp_custom_message' => $this->whatsapp_custom_message,
            'invoice_template'        => $this->invoice_template,
        ]);

        $this->settings->save();

        cache()->forget('settings');

        $this->alert('success', __('Settings Updated successfully !'));
    }

    protected function createHTMLfile($file, string $name): string
    {
        $extension = $file->extension();
        $data = File::get($file->getRealPath());
        $base64 = 'data:image/'.$extension.';base64,'.base64_encode($data);

        $html = sprintf(
            '<div><img style="width: 100%%; display: block;" src="%s"></div>',
            $base64
        );

        $path = public_path('print/'.$name.'.html');
        File::put($path, $html);

        return $base64;
    }
}
