<div>
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Show Cash Register in ') }} {{ $cashRegister?->warehouse?->name }}
        </x-slot>

        <x-slot name="content">
            <p class="text-center text-md mb-4">
                {{ __('Please review the transaction and payments ') }}
                {{ format_date($cashRegister?->created_at) }}
            </p>
            <div class="flex">
                <div class="w-full">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>{{ __('Cash in Hand') }}:</td>
                                <td class="text-right">{{ format_currency($cashRegister?->cash_in_hand) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Total Sale Amount') }}:</td>
                                <td class="text-right">{{ format_currency($total_sale_amount) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Total Payment') }}:</td>
                                <td class="text-right">{{ format_currency($total_payment) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Cash Payment') }}:</td>
                                <td class="text-right">{{ format_currency($cash_payment) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Cheque Payment') }}:</td>
                                <td class="text-right">{{ format_currency($cheque_payment) }}</td>
                            </tr>

                            <tr>
                                <td>{{ __('Total Sale Return') }}:</td>
                                <td class="text-right">{{ format_currency($total_sale_return) }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Total Expense') }}:</td>
                                <td class="text-right">{{ format_currency($total_expense) }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ __('Total Cash') }}:</strong></td>
                                <td class="text-right">{{ format_currency($total_cash) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($cashRegister?->status == 1)
                <p class="text-center text-md mb-4">
                    {{ __('You can not edit or delete after closing the register') }}
                </p>
                <p class="text-center">
                    <x-button danger type="button" wire:click="close" wire:loading.attr="disabled">
                        <i class="fas fa-lock mr-2"></i> {{ __('Close Register') }}
                    </x-button>
                </p>
            @endif
        </x-slot>
    </x-modal>
</div>
