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
 * @property string|null $company_logo
 * @property int $default_currency_id
 * @property string $default_currency_position
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
 * @property string|null $invoice_header
 * @property int $backup_status
 * @property string|null $backup_schedule
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereBackupSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereBackupStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCompanyLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereInvoiceHeader($value)
 * @property string $default_date_format
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDefaultDateFormat($value)
 * @property string|null $telegram_channel
 * @property string|null $invoice_footer_text
 * @property string $saleReturn_prefix
 * @property string $purchaseReturn_prefix
 * @property string $salePayment_prefix
 * @property string $purchasePayment_prefix
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereInvoiceFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePurchasePaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting wherePurchaseReturnPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSalePaymentPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSaleReturnPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereTelegramChannel($value)
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
