<?php
$files = glob(__DIR__ . '/app/Models/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);

    $content = str_replace(
        'get: fn ($value) => $value / 100,',
        'get: fn (int|float|null $value): float => (float) $value / 100,',
        $content
    );

    $content = str_replace(
        'set: fn ($value) => $value * 100,',
        'set: fn (int|float|null $value): float => (float) $value * 100,',
        $content
    );

    $content = str_replace(
        'get: static fn ($value) => $value / 100,',
        'get: static fn (int|float|null $value): float => (float) $value / 100,',
        $content
    );

    $content = str_replace(
        'set: static fn ($value) => $value * 100,',
        'set: static fn (int|float|null $value): float => (float) $value * 100,',
        $content
    );

    file_put_contents($file, $content);
}
echo "Done replacing get/set\n";
