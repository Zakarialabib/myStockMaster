<table style="width:100%; margin:20px 0">
    <tr>
        @if ($style === 'centered')
            <td style="width:100%; text-align:center;">
                <img src="{{ $logo }}" style="max-height:60px;" />
                <div style="font-size:10pt;">
                    {{ settings()->company_name }}<br />
                    {{ settings()->company_address }}<br />
                    {{ settings()->company_phone }}<br />
                </div>
            </td>
        @elseif($style === 'right')
            <td style="width:50%">
                <img src="{{ $logo }}" style="max-height:60px;" />
            </td>
            <td class="text-right" style="width:50%;">
                <div style="font-size:10pt;">
                    {{ settings()->company_name }}<br />
                    {{ settings()->company_address }}<br />
                    {{ settings()->company_phone }}<br />
                </div>
            </td>
        @elseif($style === 'left')
            <td style="width:50%">
                <div style="font-size:10pt;">
                    {{ settings()->company_name }}<br />
                    {{ settings()->company_address }}<br />
                    {{ settings()->company_phone }}<br />
                </div>
            </td>
            <td style="width:50%; text-align:right;">
                <img src="{{ $logo }}" style="max-height:60px;" />
            </td>
        @endif
    </tr>
</table>
<table style="width:100%; border-collapse:collapse">
    <thead>
        <tr>
            <th style="padding:8px; border:1px solid #ddd; text-align:center; width:50%; valign:top">
                @if (isset($customer))
                    {{ $customer->name }} - {{ __('Sale Details') }}
                @elseif(isset($supplier))
                    {{ $supplier->name }} - {{ __('Purchase Details') }}
                @endif
            </th>

            <th style="padding:8px; border:1px solid #ddd; text-align:center; width:50%; valign:top">
                @if (isset($customer))
                    {{ __('Customer Information') }}
                @elseif(isset($supplier))
                    {{ __('Supplier Information') }}
                @endif
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:8px; border:1px solid #ddd; width:50%">
                @if (isset($customer))
                    {{ __('Name') }}: {{ $customer->name }}<br>
                    {{ __('Address') }}: {{ $customer->address }}<br>
                    {{ __('Phone') }}: {{ $customer->phone }}<br>
                    {{ __('Email') }}: {{ $customer->email }}
                @elseif(isset($supplier))
                    {{ __('Name') }}: {{ $supplier->name }}<br>
                    {{ __('Address') }}: {{ $supplier->address }}<br>
                    {{ __('Phone') }}: {{ $supplier->phone }}<br>
                    {{ __('Email') }}: {{ $supplier->email }}
                @endif
            </td>
            @if (isset($sale))
                <td style="padding:8px; border:1px solid #ddd; width:50%">
                    {{ __('Reference') }}: {{ $sale->reference }}<br>
                    {{ __('Date') }}: {{ format_date($sale->date) }}<br>
                    {{ __('Status') }}:
                    @if ($sale->status == \App\Enums\SaleStatus::PENDING)
                        <span class="badge badge-warning">{{ __('Pending') }}</span>
                    @elseif ($sale->status == \App\Enums\SaleStatus::ORDERED)
                        <span class="badge badge-info">{{ __('Ordered') }}</span>
                    @elseif($sale->status == \App\Enums\SaleStatus::COMPLETED)
                        <span class="badge badge-success">{{ __('Completed') }}</span>
                    @endif
                    <br>
                    {{ __('Payment Status') }}:
                    @if ($sale->payment_status == \App\Enums\PaymentStatus::PAID)
                        <span class="badge badge-success">{{ __('Paid') }}</span>
                    @elseif ($sale->payment_status == \App\Enums\PaymentStatus::PARTIAL)
                        <span class="badge badge-warning">{{ __('Partially Paid') }}</span>
                    @elseif($sale->payment_status == \App\Enums\PaymentStatus::DUE)
                        <span class="badge badge-danger">{{ __('Due') }}</span>
                    @endif
                </td>
            @elseif(isset($purchase))
                <td style="padding:8px; border:1px solid #ddd; width:50%">
                    {{ __('Reference') }}: {{ $purchase->reference }}<br>
                    {{ __('Date') }}:{{ format_date($purchase->date) }}<br>
                    <strong>{{ __('Status') }}:</strong>
                    @if ($purchase->status == \App\Enums\PurchaseStatus::PENDING)
                        <span clacc="badge badge-warning">{{ __('Pending') }}</span>
                    @elseif ($purchase->status == \App\Enums\PurchaseStatus::ORDERED)
                        <span clacc="badge badge-info">{{ __('Ordered') }}</span>
                    @elseif($purchase->status == \App\Enums\PurchaseStatus::COMPLETED)
                        <span clacc="badge badge-success">{{ __('Completed') }}</span>
                    @elseif($purchase->status == \App\Enums\PurchaseStatus::RETURNED)
                        <span clacc="badge badge-success">{{ __('Returned') }}</span>
                    @endif
                    <strong>{{ __('Payment Status') }}:</strong><br>
                    @if ($purchase->payment_status == \App\Enums\PaymentStatus::PAID)
                        <span clacc="badge badge-success">{{ __('Paid') }}</span>
                    @elseif ($purchase->payment_status == \App\Enums\PaymentStatus::PARTIAL)
                        <span clacc="badge badge-warning">{{ __('Partially Paid') }}</span>
                    @elseif($purchase->payment_status == \App\Enums\PaymentStatus::DUE)
                        <span clacc="badge badge-danger">{{ __('Due') }}</span>
                    @endif
                </td>
            @endif
        </tr>
    </tbody>
</table>
