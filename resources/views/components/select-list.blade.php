<div>
    <div wire:ignore class="w-full">
        @if(isset($attributes['multiple']))
            <div id="{{ $attributes['id'] }}-btn-container" class="mb-3">
                <button type="button" class="btn btn-info btn-sm select-all-button">{{ __('Select all') }}</button>
                <button type="button" class="btn btn-info btn-sm deselect-all-button">{{ __('Deselect all') }}</button>
            </div>
        @endif
        <select class="select2 p-3 leading-5 bg-white dark:bg-dark-eval-2 text-zinc-700 dark:text-zinc-300 rounded border border-zinc-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500" data-minimum-results-for-search="Infinity" data-placeholder="{{ __('Choose option') }}" {{ $attributes }}>
            @if(!isset($attributes['multiple']))
                <option></option>
            @endif
            @foreach($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('page_scripts')
<script>
    document.addEventListener("livewire:load", () => {
        let el = $('#{{ $attributes['id'] }}')
        let buttonsId = '#{{ $attributes['id'] }}-btn-container'

        function initButtons() {
            $(buttonsId + ' .select-all-button').click(function (e) {
                el.val(_.map(el.find('option'), opt => $(opt).attr('value')))
                el.trigger('change')
            })

            $(buttonsId + ' .deselect-all-button').click(function (e) {
                el.val([])
                el.trigger('change')
            })
        }

        function initSelect () {
            initButtons()
            el.select2({
                placeholder: '{{ __('Choose option') }}',
                allowClear: !el.attr('required')
            })
        }

        initSelect()

        Livewire.hook('message.processed', (message, component) => {
            initSelect()
        });

        el.on('change', function (e) {
            let data = $(this).select2("val")
            if (data === "") {
                data = null
            }
            @this.set('{{ $attributes['wire:model'] }}', data)
        });
    });
</script>
@endpush