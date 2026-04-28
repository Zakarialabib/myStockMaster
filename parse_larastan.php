<?php

declare(strict_types=1);
$content = file_get_contents('larastan-output.md');
$data = json_decode($content, true);

foreach ($data['files'] as $filePath => $fileData) {
    if (strpos($filePath, 'app/Livewire/Reports') !== false || strpos($filePath, 'app/Livewire/Analytics') !== false || strpos($filePath, 'app/Actions/Analytics') !== false) {
        echo "\nFile: {$filePath}\n";
        foreach ($fileData['messages'] as $error) {
            echo "  - Line {$error['line']}: {$error['message']}\n";
        }
    }
}
