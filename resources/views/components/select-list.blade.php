<div wire:ignore class="w-full">
    <select class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1" data-minimum-results-for-search="Infinity" data-placeholder="{{ __('Choose option') }}" {{ $attributes }}>
        @if(!isset($attributes['multiple']))
            <option></option>
        @endif
        @foreach($options as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>