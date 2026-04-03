<?php
$files = glob(__DIR__ . '/app/Models/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    // Convert protected $casts = [ ... ]; to protected function casts(): array { return [ ... ]; }
    if (preg_match('/protected\s+\$casts\s*=\s*\[(.*?)\];/s', $content, $matches)) {
        $castsArray = $matches[1];
        $newMethod = "protected function casts(): array\n    {\n        return [" . $castsArray . "];\n    }";
        $content = str_replace($matches[0], $newMethod, $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
