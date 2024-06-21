<div>
    <x-modal wire:model.live="showPayments">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Purchase Payment') }}
            </h2>
            <div class="flex justify-end">
                @if ($purchase?->due_amount > 0)
                    <x-button wire:click="$dispatch('paymentModal', {{ $purchase_id }})"
                        x-on:click="$wire.set('showPayments', false)" primary type="button">
                        {{ __('Add Payment') }}
                    </x-button>
                @endif
            </div>
        </x-slot>
        <x-slot name="content">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Amount') }}</x-table.th>
                    <x-table.th>{{ __('Due Amount') }}</x-table.th>
                    <x-table.th>{{ __('Payment Method') }}</x-table.th>
                    <x-table.th>{{ __('Actions') }}</x-table.th>
                </x-slot>
                <x-table.tbody>

                    @forelse ($purchasepayments as $purchasepayment)
                        <x-table.tr>
                            <x-table.td>{{ $purchasepayment->created_at }}</x-table.td>
                            <x-table.td>
                                {{ format_currency($purchasepayment->amount) }}
                            </x-table.td>
                            <x-table.td>
                                {{ format_currency($purchasepayment->purchase->due_amount) }}
                            </x-table.td>
                            <x-table.td>{{ $purchasepayment->payment_method }}</x-table.td>
                            <x-table.td>
                                {{-- @can('purchase_payment_access')
                                    <x-button wire:click="$dispatch('paymentModal', {{ $purchasepayment->id }} )" type="button"
                                        primary>
                                        <i class="fa fa-pen"></i>
                                    </x-button>
                                @endcan
                                <x-button wire:click="delete({{ $purchasepayment->id }})" danger type="button">
                                    <i class="fa fa-trash"></i>
                                </x-button> --}}
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="5">{{ __('No data found') }}</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            <div class="mt-4">
                {{-- {{ $purchase->purchasepayments->links() }} --}}
            </div>

        </x-slot>
    </x-modal>

</div>
