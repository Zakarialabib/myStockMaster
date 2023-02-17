<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $company_name
 * @property string $company_email
 * @property string $company_phone
 * @property string|null $site_logo
 * @property int $default_currency_id
 * @property string $default_currency_position
 * @property string $notification_email
 * @property string $footer_text
 * @property string $company_address
 * @property int|null $default_client_id
 * @property int|null $default_warehouse_id
 * @property string $default_language
 * @property int $is_invoice_footer
 * @property string|null $invoice_footer
 * @property string|null $company_tax
 * @property int $is_rtl
 * @property string $sale_prefix
 * @property string $purchase_prefix
 * @property string $quotation_prefix
 * @property string $salepayment_prefix
 * @property string $purchasepayment_prefix
 * @property int $show_email
 * @property int $show_address
 * @property int $show_order_tax
 * @property int $show_discount
 * @property int $show_shipping
 * @property string $receipt_printer_type
 * @property int|null $printer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Currency|null $currency
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultCurrencyPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereInvoiceFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereIsInvoiceFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereIsRtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereNotificationEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePrinterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePurchasePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePurchasepaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereQuotationPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereReceiptPrinterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSalePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSalepaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShowAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShowDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShowEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShowOrderTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShowShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSiteLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @property string|null $woocommerce_store_url
 * @property string|null $woocommerce_api_key
 * @property string|null $woocommerce_api_secret
 * @property string|null $shopify_store_url
 * @property string|null $shopify_api_key
 * @property string|null $shopify_api_secret
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShopifyApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShopifyApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereShopifyStoreUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWoocommerceApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWoocommerceApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWoocommerceStoreUrl($value)
 * @property string|null $custom_store_url
 * @property string|null $custom_api_key
 * @property string|null $custom_api_secret
 * @property string|null $custom_last_sync
 * @property string|null $custom_products
 * @property string|null $custom_orders
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomApiKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomApiSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomLastSync($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCustomStoreUrl($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $guarded = [];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'default_currency_id', 'id');
    }
}
