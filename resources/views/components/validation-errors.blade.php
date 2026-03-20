@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-error-50 dark:bg-error-900/30 border border-error-200 dark:border-error-800 rounded-xl p-4']) }}>
        <div class="flex items-center gap-2 font-bold text-error-700 dark:text-error-400">
            <i class="fas fa-exclamation-triangle"></i>
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-2 list-disc list-inside text-sm text-error-600 dark:text-error-300 font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
