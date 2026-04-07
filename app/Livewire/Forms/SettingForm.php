<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SettingForm extends Form
{
    public ?Setting $setting = null;

    public ?string $invoice_header = null;

    public ?string $invoice_footer = null;

    public ?string $invoice_template = null;

    public ?string $image = '';

    public ?string $site_logo = null;

    public ?string $site_title = null;

    public ?string $social_facebook = null;

    public ?string $social_twitter = null;

    public ?string $social_instagram = null;

    public ?string $social_linkedin = null;

    public ?string $social_whatsapp = null;

    public ?string $social_tiktok = null;

    public ?string $site_favicon = null;

    #[Validate('required|string|min:1|max:255')]
    public string $company_name = '';

    #[Validate('required|string|min:1|max:255')]
    public string $company_email = '';

    #[Validate('required|string|min:1|max:255')]
    public string $company_phone = '';

    #[Validate('required|string|min:1|max:255')]
    public string $company_address = '';

    #[Validate('nullable|string|min:0|max:255')]
    public ?string $company_tax = null;

    #[Validate('nullable|string|min:0|max:255')]
    public ?string $telegram_channel = null;

    #[Validate('required|integer|min:0|max:192')]
    public int $default_currency_id = 1;

    #[Validate('required|string|min:1|max:255')]
    public string $default_currency_position = 'prefix';

    #[Validate('required|string|min:1|max:255')]
    public string $default_date_format = 'Y-m-d';

    public ?int $default_client_id = null;

    public ?int $default_warehouse_id = null;

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

    public ?string $notification_email = null;

    public ?string $footer_text = null;

    public bool $is_ecommerce_active = false;

    public function setSetting(Setting $setting): void
    {
        $this->setting = $setting;

        $this->site_logo = $setting->site_logo;
        $this->site_title = $setting->site_title;
        $this->site_favicon = $setting->site_favicon;
        $this->company_name = $setting->company_name ?? '';
        $this->company_email = $setting->company_email ?? '';
        $this->company_phone = $setting->company_phone ?? '';
        $this->company_address = $setting->company_address ?? '';
        $this->company_tax = $setting->company_tax;
        $this->telegram_channel = $setting->telegram_channel;
        $this->default_currency_id = $setting->default_currency_id ?? 1;
        $this->default_currency_position = $setting->default_currency_position ?? 'prefix';
        $this->default_date_format = $setting->default_date_format ?? 'Y-m-d';
        $this->default_client_id = $setting->default_client_id;
        $this->default_warehouse_id = $setting->default_warehouse_id;
        $this->invoice_footer_text = $setting->invoice_footer_text;
        $this->sale_prefix = $setting->sale_prefix;
        $this->saleReturn_prefix = $setting->saleReturn_prefix;
        $this->purchase_prefix = $setting->purchase_prefix;
        $this->purchaseReturn_prefix = $setting->purchaseReturn_prefix;
        $this->quotation_prefix = $setting->quotation_prefix;
        $this->salePayment_prefix = $setting->salePayment_prefix;
        $this->purchasePayment_prefix = $setting->purchasePayment_prefix;
        $this->expense_prefix = $setting->expense_prefix;
        $this->delivery_prefix = $setting->delivery_prefix;
        $this->is_rtl = (bool) $setting->is_rtl;

        $this->social_facebook = $setting->social_facebook;
        $this->social_twitter = $setting->social_twitter;
        $this->social_instagram = $setting->social_instagram;
        $this->social_linkedin = $setting->social_linkedin;
        $this->social_whatsapp = $setting->social_whatsapp;
        $this->social_tiktok = $setting->social_tiktok;
        $this->head_tags = $setting->head_tags;
        $this->body_tags = $setting->body_tags;
        $this->seo_meta_title = $setting->seo_meta_title;
        $this->seo_meta_description = $setting->seo_meta_description;
        $this->whatsapp_custom_message = $setting->whatsapp_custom_message;
        $this->invoice_template = $setting->invoice_template;
        $this->notification_email = $setting->notification_email ?? null;
        $this->footer_text = $setting->footer_text ?? null;
        $this->is_ecommerce_active = (bool) $setting->is_ecommerce_active;
    }

    public function update(): void
    {
        $this->validate();

        $this->setting->update([
            'site_title' => $this->site_title,
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
            'notification_email' => $this->notification_email,
            'footer_text' => $this->footer_text,
            'is_ecommerce_active' => $this->is_ecommerce_active,
        ]);

        $this->setting->save();
    }
}
