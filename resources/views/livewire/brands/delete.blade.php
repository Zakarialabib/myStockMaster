<div>
    <!-- Delete Modal -->
    <x-modal wire:model="showModal" name="deleteModal">
        <x-slot name="title">
            {{ __('Delete Brand') }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form wire:submit="delete">
                <div class="space-y-4">
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-md" role="alert">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium">
                                    {!! __('Are you sure you want to delete the <strong class="font-bold">:name</strong> brand? This action cannot be undone.', [
                                        'name' => e($brand->name) 
                                    ]) !!}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 w-full">
                        <!-- Bouton Annuler -->
                        <x-button secondary type="button" wire:click="$set('showModal', false)">
                            {{ __('Cancel') }}
                        </x-button>

                        <!-- Bouton Supprimer -->
                        <x-button danger type="submit" wire:loading.attr="disabled" wire:click="delete()">
                            {{ __('Delete') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
    <!-- End Delete Modal -->
</div>
