@props(['footer' => null, 'options'])

<div x-data="{ model: @entangle($attributes->wire('model')) }" 
x-init="select2 = $($refs.select)
    .not('.select2-hidden-accessible')
    .select2();
select2.on('select2:select', (event) => {
    if (event.target.hasAttribute('multiple')) { model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); } else { model = event.params.data.id }
});
select2.on('select2:unselect', (event) => {

    if (event.target.hasAttribute('multiple')) { model = Array.from(event.target.options).filter(option => option.selected).map(option => option.value); } else { model = event.params.data.id }
});
select2.on('select2:opening', (event) => {
    loading = true;
});
select2.on('select2:open', (event) => {
    loading = false;
});
$watch('model', (value) => {
    select2.val(value).trigger('change');
});" 
wire:ignore>
    <select x-ref="select" data-placeholder="{{ __('Choose option') }}"
        {{ $attributes->merge(['class' => 'form-control ']) }}>
        @if (!isset($attributes['multiple']))
            <option></option>
        @endif
        @foreach ($options as $key => $value)
            <option value="{{ $value['id'] }}"
                selected="{{ old($attributes['wire:model'], $value['id']) == $value['id'] ? 'selected' : '' }}">
                {{ $value['text'] }}
            </option>
        @endforeach
        <div class="select-footer">
            {{ $footer }}
        </div>
        <button class="btn btn-danger clear" type="button" x-if="!$refs.select.hasAttribute('required')"
            x-on:click="model = null">Clear</button>
    </select>
</div>

@section('page_header')
    <style>
        .loading-icon {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: grey;
            padding: 0.5rem;
        }
    </style>
@endsection
