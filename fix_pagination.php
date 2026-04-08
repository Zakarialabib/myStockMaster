<?php

$dir = new RecursiveDirectoryIterator('resources/views/livewire');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/\.blade\.php$/', RegexIterator::GET_MATCH);
$fileList = array_keys(iterator_to_array($files));

foreach ($fileList as $bladeFile) {
    $content = file_get_contents($bladeFile);
    if (str_contains($content, '<x-slot name="pagination">')) {
        $content = str_replace('<x-slot name="pagination">', '<div class="px-6 py-3">', $content);
        
        // We only want to replace the first </x-slot> after the <div class="px-6 py-3">, 
        // but since we don't know exactly where, maybe we can use regex.
        // Actually, the simplest is to replace `<x-slot name="pagination">` with `<div class="px-6 py-3">`
        // and since x-slot must be closed by `</x-slot>`, we can find the matching `</x-slot>` and replace it with `</div>`.
        // A safer way:
        $content = preg_replace('/<x-slot name="pagination">(.*?)<\/x-slot>/s', '<div class="px-6 py-3">$1</div>', $content);
        
        file_put_contents($bladeFile, $content);
        echo "Fixed pagination in $bladeFile\n";
    }
}
