@props(['options'])

<div
    x-data="{
        model: @entangle($attributes->wire('model')),
    }"
    x-init="
        select2 = $($refs.select)
            .not('.select2-hidden-accessible')
            .select2({
                theme: 'classic',
                dropdownAutoWidth: true,
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 10,
            });
        select2.on('select2:select', (event) => {
            if (event.target.hasAttribute('multiple')) { model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); } else { model = event.params.data.id }
        });
        select2.on('select2:unselect', (event) => {
           
            if (event.target.hasAttribute('multiple')) { model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); } else { model = event.params.data.id }
        });
        $watch('model', (value) => {
            select2.val(value).trigger('change');
        });
    "
    wire:ignore
 >
    <select x-ref="select" id="{{ $attributes['id'] }}-select" {{ $attributes->merge(['class' => 'select2 w-full p-3 leading-5 bg-white rounded border border-zinc-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500 ']) }}>
        @foreach ($options as $key => $value)
            <option value="{{ $value['id'] }}"
                selected="{{ old($attributes['wire:model'], $value['id']) == $value['id'] ? 'selected' : '' }}">
                {{ $value['name'] }}
            </option>
        @endforeach
    </select>
</div>

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@endonce