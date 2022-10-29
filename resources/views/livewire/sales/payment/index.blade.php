
<div>
    <x-modal wire:model="showPayments">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Sale Payment') }}
            </h2>
            <div class="flex justify-end">
                <x-button wire:click="$emit('paymentModal', {{ $sale->id}})" primary type="button">
                    {{ __('Add Payment') }}
                </x-button>
                <x-button wire:click="$set('showPayments', false)" type="button" secondary>
                    {{ __('Cancel') }}
                </x-button>
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
                    {{-- @dd($salepayments) --}}
                    @forelse ($salepayments as $salepayment)
                        <x-table.tr>
                            <x-table.td>{{ $salepayment->created_at }}</x-table.td>
                            <x-table.td>{{ $salepayment->amount }}</x-table.td>
                            <x-table.td>{{ $salepayment->sale->due_amount }}</x-table.td>
                            <x-table.td>{{ $salepayment->payment_method }}</x-table.td>
                            <x-table.td>
                                @can('access_sale_payments')
                                <x-button wire:click="$emit('paymentModal', {{$salepayment->id}} )"
                                    type="button" primary>
                                    {{ __('Edit') }}
                                </x-button>
                                <a href="{{ route('sale-payments.edit', [$salepayment->sale->id, $salepayment->id]) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                {{-- <x-button wire:click="delete({{ $salepayment->id }})"
                                    class="bg-red-500 hover:bg-red-700">
                                    {{ __('Delete') }}
                                </x-button> --}}
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
                {{-- {{ $sale->salepayments->links() }} --}}
            </div>
           
        </x-slot>
    </x-modal>

</div>
