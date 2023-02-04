<div>
    <div>
        <label>Select invoice template:</label>
        <select wire:model="invoiceTemplate">
            @foreach ($templates as $template)
                <option value="{{ $template }}">{{ $template }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>{{__('Create new template')}}:</label>
        <div class="w-full grid grid-cols-2 gap-2 px-2">
            <div class="inline-flex px-2">
                <label for=""></label>
                <x-input type="text" wire:model.lazy="" />
            </div>
            <div class="inline-flex px-2">
                <label for=""></label>
                <x-input type="text" wire:model.lazy="" />
            </div>
        </div>


    <div>
        <h2>Invoice Preview:</h2>
        <div wire:ignore>
            {{-- $invoiceTemplate --}}
            <iframe src="" frameborder="0"></iframe>
        </div>
    </div>
</div>
