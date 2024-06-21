<div>
    <div wire:ignore class="w-full">
        <select  {{ $attributes->merge(['class' => 'select2 w-full p-3 leading-5 bg-white rounded border border-zinc-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500 ']) }}>
            @foreach ($options as $key => $value)
                <option value="{{ $key }}">{{ $value['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>
@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    @endpush

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
@endonce

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let el = $('#{{ $attributes['id'] }}')

            function initSelect() {
                el.select2({
                    dropdownAutoWidth: true,
                    language: "{{ App::getLocale() }}",
                    placeholder: '{{ __('Select your option') }}',
                    minimumResultsForSearch: 5,
                    allowClear: !el.attr('required'),
                    width: '100%'
                })
            }
            initSelect()
            el.on('change', function(e) {
                @this.set('{{ $attributes['wire:model.live'] }}', el.val());
            });
        });
    </script>
@endpush
