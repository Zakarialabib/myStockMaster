<div wire:ignore>
    <div class="flatpickr flatpickr-{{ $attributes['id'] }} relative">
        @if(!isset($attributes['required']))
            <div class="absolute inset-y-0 left-0 flex items-center">
                <button id="clear-{{ $attributes['id'] }}" type="button" class="text-rose-600 w-10 h-full" data-clear>
                    <i class="far fa-times-circle"></i>
                </button>
            </div>
        @endif

        <input type="text" class="p-3 leading-5 bg-white dark:bg-dark-eval-2 text-zinc-700 dark:text-zinc-300 rounded border border-zinc-300 mb-1 text-sm w-full focus:shadow-outline-blue focus:border-blue-500" {{ $attributes }} data-input>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
    function update(value) {
        let el = document.getElementById('clear-{{ $attributes['id'] }}')

        if (value === '') {
            value = null

            if (el !== null) {
                el.classList.add('invisible')
            }
        } else if (el !== null) {
            el.classList.remove('invisible')
        }

@this.set('{{ $attributes['wire:model'] }}', value)
    }

@if($attributes['picker'] === 'date')
        let el = flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            dateFormat: "{{ config('project.flatpickr_date_format') }}",
            wrap: true,
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            }
        })
@elseif($attributes['picker'] === 'time')
        let el = flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            enableTime: true,
            // enableSeconds: true,
            noCalendar: true,
            time_24hr: true,
            wrap: true,
            dateFormat: "{{ config('project.flatpickr_time_format') }}",
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            }
        })
@else
        let el = flatpickr('.flatpickr-{{ $attributes['id'] }}', {
            enableTime: true,
            time_24hr: true,
            wrap: true,
            // enableSeconds: true,
            dateFormat: "{{ config('project.flatpickr_datetime_format') }}",
            onChange: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            },
            onReady: (SelectedDates, DateStr, instance) => {
                update(DateStr)
            }
        })
@endif
});
    </script>
@endpush