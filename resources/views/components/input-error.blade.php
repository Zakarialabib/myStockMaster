@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-error-600 dark:text-error-400 font-medium space-y-1 mt-1.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1">
                <i class="fas fa-exclamation-circle text-xs"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
