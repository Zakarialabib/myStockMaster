<div>
    <div>
        <label>Select invoice template:</label>
        <select wire:model="invoiceTemplate">
            @foreach($templates as $template)
                <option value="{{ $template }}">{{ $template }}</option>
            @endforeach
        </select>
        <button wire:click="downloadInvoice" wire:loading.attr="disabled" wire:target="downloadInvoice">
            {{ __('Download Invoice') }}
        </button>
    </div>
    
    <div>
        <h2>Invoice Preview:</h2>
        <div wire:ignore>
            @include("invoice_templates.$invoiceTemplate")
        </div>
    </div>
      
</div>
