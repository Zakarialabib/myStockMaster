<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Livewire'));
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        // Find function name($var1, $var2) -> function name(mixed $var1, mixed $var2)
        // Using preg_replace_callback
        $content = preg_replace_callback('/function\s+[a-zA-Z0-9_]+\s*\(([^)]*)\)/', function($matches) {
            $params = explode(',', $matches[1]);
            $newParams = [];
            foreach ($params as $param) {
                $param = trim($param);
                if (empty($param)) continue;
                // If it starts with $ (no type)
                if (preg_match('/^&?\$[a-zA-Z0-9_]+/', $param)) {
                    $newParams[] = 'mixed ' . $param;
                } else {
                    $newParams[] = $param;
                }
            }
            return str_replace($matches[1], implode(', ', $newParams), $matches[0]);
        }, $content);
        file_put_contents($file->getPathname(), $content);
    }
}
