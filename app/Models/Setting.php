<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property int                             $id
 * @property string                          $company_name
 * @property string|null                     $company_email
 * @property string|null                     $company_phone
 * @property string|null                     $company_address
 * @property string|null                     $company_tax
 * @property string|null                     $telegram_channel
 * @property int|null                        $default_currency_id
 * @property string|null                     $default_currency_position
 * @property string|null                     $default_date_format
 * @property int|null                        $default_client_id
 * @property int|null                        $default_warehouse_id
 * @property string                          $default_language
 * @property int                             $is_rtl
 * @property string|null                     $invoice_footer_text
 * @property string|null                     $invoice_header
 * @property string|null                     $invoice_footer
 * @property string                          $sale_prefix
 * @property string                          $saleReturn_prefix
 * @property string                          $purchase_prefix
 * @property string                          $purchaseReturn_prefix
 * @property string                          $quotation_prefix
 * @property string                          $salePayment_prefix
 * @property string                          $purchasePayment_prefix
 * @property int                             $backup_status
 * @property string|null                     $backup_schedule
 * @property string|null                     $invoice_control
 * @property array<array-key, mixed>|null    $analytics_control
 * @property array<array-key, mixed>|null    $template_styles
 * @property array<array-key, mixed>|null    $mail_styles
 * @property array<array-key, mixed>|null    $app_style
 * @property array<array-key, mixed>|null    $notification_triggers
 * @property string                          $pos_post_checkout_action
 * @property string                          $receipt_printer_type
 * @property int|null                        $printer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool                            $installation_completed
 * @property int                             $multi_warehouse_sale
 * @property string|null                     $site_title
 * @property string|null                     $site_logo
 * @property string|null                     $site_favicon
 * @property string|null                     $social_facebook
 * @property string|null                     $social_twitter
 * @property string|null                     $social_instagram
 * @property string|null                     $social_linkedin
 * @property string|null                     $social_whatsapp
 * @property string|null                     $social_tiktok
 * @property string|null                     $head_tags
 * @property string|null                     $body_tags
 * @property string|null                     $seo_meta_title
 * @property string|null                     $seo_meta_description
 * @property string|null                     $whatsapp_custom_message
 * @property string                          $invoice_template
 * @property string                          $expense_prefix
 * @property string                          $delivery_prefix
 * @property int                             $show_email
 * @property int                             $show_address
 * @property int                             $show_order_tax
 * @property int                             $show_discount
 * @property int                             $show_shipping
 * @property-read Currency|null $currency
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereAnalyticsControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereBackupSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereBackupStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereBodyTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCompanyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCompanyTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultCurrencyPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDefaultWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDeliveryPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereExpensePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereHeadTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInstallationCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInvoiceControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInvoiceFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInvoiceFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInvoiceHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereInvoiceTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereIsRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereMultiWarehouseSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereNotificationTriggers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePosPostCheckoutAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePrinterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePurchasePaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePurchasePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting wherePurchaseReturnPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereQuotationPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereReceiptPrinterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSalePaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSalePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSaleReturnPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSeoMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSeoMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShowAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShowDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShowEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShowOrderTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereShowShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSiteFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSiteLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSiteTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialTiktok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereSocialWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereTelegramChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereWhatsappCustomMessage($value)
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Setting extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'app_style' => 'array',
            'analytics_control' => 'array',
            'template_styles' => 'array',
            'mail_styles' => 'array',
            'invoice_control' => 'array',
            'notification_triggers' => 'array',
            'installation_completed' => 'boolean',
            'is_rtl' => 'boolean',
            'backup_status' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }

    /** Set a specific setting value */
    public static function set(string $key, mixed $value): void
    {
        $setting = static::query()->first();

        if ($setting) {
            $setting->update([$key => $value]);
            cache()->forget('settings');
        }
    }

    /** Get a specific setting value with default fallback */
    public static function get(string $key, mixed $default = null)
    {
        $settings = settings();

        return $settings ? ($settings->{$key} ?? $default) : $default;
    }
}
