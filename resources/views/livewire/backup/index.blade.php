<div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                {{ __('Id') }}
            </x-table.th>
            <x-table.th>
                {{ __('Date') }}
            </x-table.th>
            <x-table.th>
                {{ __('Size') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
            </tr>
        </x-slot>
        <x-table.tbody>
            @forelse($data as $backup)
                <x-table.tr>
                    <x-table.td>
                        {{ $backup->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $backup->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ $backup->size }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <x-button danger wire:click="delete('{{ $backup->id }}')" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="10" class="text-center">
                        {{ __('No entries found.') }}
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', brandId => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', brandId)
                    }
                })
            })
        })
    </script>
@endpush
