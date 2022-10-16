<div>
    <x-modal wire:model="showPayments">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Sale Payment') }}
            </h2>
            <x-button wire:click="paymentModal" primary type="button">
                {{ __('Add Payment') }}
            </x-button>
        </x-slot>
        <x-slot name="content">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Amount') }}</x-table.th>
                    <x-table.th>{{ __('Payment Method') }}</x-table.th>
                    <x-table.th>{{ __('Actions') }}</x-table.th>
                </x-slot>
                <x-table.tbody>
                    @forelse ($salespayment as $salepayment)
                        <x-table.tr>
                            <x-table.td>{{ $salepayment->created_at }}</x-table.td>
                            <x-table.td>{{ $salepayment->amount }}</x-table.td>
                            <x-table.td>{{ $salepayment->payment_method }}</x-table.td>
                            <x-table.td>
                                <x-button wire:click="paymentModal({{ $salepayment->sale->id }}, {{ $salepayment->id }})" type="button"
                                    primary>
                                    {{ __('Edit') }}
                                </x-button>

                                <x-button wire:click="delete({{ $salepayment->id }})"
                                    class="bg-red-500 hover:bg-red-700">
                                    {{ __('Delete') }}
                                </x-button>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="3">{{ __('No data found') }}</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            <div class="mt-4">
                {{ $salespayment->links() }}
            </div>
            <div class="w-full flex justify-start">
                <x-button wire:click="$set('showPayments', false)" type="button" secondary>
                    {{ __('Cancel') }}
                </x-button>
            </div>
        </x-slot>
    </x-modal>
</div>
