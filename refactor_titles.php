<?php

$dir = new RecursiveDirectoryIterator('resources/views/livewire');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/\.blade\.php$/', RegexIterator::GET_MATCH);
$fileList = array_keys(iterator_to_array($files));

foreach ($fileList as $bladeFile) {
    $content = file_get_contents($bladeFile);
    if (preg_match('/@section\(\'title\',\s*(.*?)\)/', $content, $matches)) {
        $titleRaw = $matches[1]; // e.g. __('Brands List') or 'Brands List'
        
        // Extract the actual string
        $titleStr = '';
        if (preg_match('/__\(\'(.*?)\'\)/', $titleRaw, $m)) {
            $titleStr = $m[1];
        } elseif (preg_match('/\'(.*?)\'/', $titleRaw, $m)) {
            $titleStr = $m[1];
        }
        
        if ($titleStr) {
            // Remove the section line
            $newContent = preg_replace('/^\s*@section\(\'title\',\s*.*?\)\s*$/m', '', $content);
            file_put_contents($bladeFile, $newContent);
            
            // Now find corresponding Livewire class
            $relativePath = str_replace('resources/views/livewire/', '', $bladeFile);
            $relativePath = str_replace('.blade.php', '.php', $relativePath);
            // e.g. brands/index.php -> Brands/Index.php
            $parts = explode('/', $relativePath);
            $parts = array_map(function($p) {
                // Convert kebab-case to PascalCase
                return str_replace(' ', '', ucwords(str_replace('-', ' ', $p)));
            }, $parts);
            
            $classPath = 'app/Livewire/' . implode('/', $parts);
            
            if (file_exists($classPath)) {
                $classContent = file_get_contents($classPath);
                
                // Check if already has Title attribute
                if (!str_contains($classContent, '#[Title')) {
                    // Add use Livewire\Attributes\Title; if not present
                    if (!str_contains($classContent, 'use Livewire\Attributes\Title;')) {
                        $classContent = preg_replace('/(namespace App\\\\Livewire.*?;)/s', "$1\n\nuse Livewire\\Attributes\\Title;", $classContent);
                    }
                    
                    // Add #[Title('...')] before class declaration
                    $classContent = preg_replace('/(class\s+[a-zA-Z0-9_]+\s+extends)/', "#[Title('$titleStr')]\n$1", $classContent);
                    
                    file_put_contents($classPath, $classContent);
                    echo "Updated $classPath with #[Title('$titleStr')]\n";
                }
            } else {
                echo "Class not found: $classPath\n";
            }
        }
    }
}
