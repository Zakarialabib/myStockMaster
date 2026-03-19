@props([
    'id' => null,
    'type' => 'text',
    'name' => null,
    'value' => null,
    'disabled' => false,
    'required' => false,
    'placeholder' => null,
    'autofocus' => false,
    'autocomplete' => null,
    'readonly' => false,
])

@php
    $attributes = $attributes->class([
        'block w-full text-sm py-2.5 px-3 rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-soft focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:focus:border-primary-500 transition-all duration-200',
        'disabled:opacity-50 disabled:bg-gray-50 dark:disabled:bg-gray-800 disabled:cursor-not-allowed' => $disabled,
        'border-error-300 dark:border-error-700 text-error-900 dark:text-error-100 placeholder-error-300 dark:placeholder-error-700 focus:border-error-500 focus:ring-error-500/20' => $errors->has($name),
    ])->merge([
        'id' => $id,
        'type' => $type,
        'name' => $name,
        'value' => $value,
        'disabled' => $disabled,
        'required' => $required,
        'placeholder' => $placeholder,
        'autofocus' => $autofocus,
        'autocomplete' => $autocomplete,
        'readonly' => $readonly,
    ]);
@endphp

<input {{ $attributes }} dir="ltr" />
