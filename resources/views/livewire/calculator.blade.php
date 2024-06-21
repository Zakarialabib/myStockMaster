{{-- Make button dropdown calculator with alpine --}}
<div>
    <div class="flex flex-col items-center">
        <div class="flex px-4 py-5 mx-auto justify-center items-center gap-4">
            <x-input type="number" wire:model.live="number1" value="{{ $number1 }}" placeholder="{{__('Number 1')}}" />
            <select class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" wire:model.live="action">
                <option>+</option>
                <option>-</option>
                <option>*</option>
                <option>/</option>
                <option>%</option>
            </select>
            <x-input type="number" wire:model.live="number2" placeholder="{{__('Number 2')}}" />
            <button wire:click="calculate"
                class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 disabled:cursor-not-allowed disabled:bg-opacity-90 rounded text-white"
                {{ $disabled ? ' disabled' : '' }}>=
            </button>
            <p class="text-3xl">{{ $result }}</p>
        </div>
    </div>
</div>
