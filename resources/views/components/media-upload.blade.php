@props([
    'name' => '',
    'title' => 'Upload File',
    'single' => true,
    'types' => '',
    'fileTypes' => '*',
    'maxSize' => 0,
    'image' => null,
    'preview' => true,
    'maxFiles' => 0,
    'file' => null,
])

<livewire:media-upload 
    {{ $attributes->wire('model') }}
    :name="$name"
    :title="$title"
    :single="$single"
    :types="$types"
    :fileTypes="$fileTypes"
    :maxSize="$maxSize"
    :image="$image"
    :preview="$preview"
    :maxFiles="$maxFiles"
/>
