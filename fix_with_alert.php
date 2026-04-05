<?php

declare(strict_types=1);

$files = explode("\n", trim(shell_exec('grep -rl "alert(" app/Livewire | xargs grep -L "WithAlert"')));

foreach ($files as $file) {
    if (empty($file)) {
        continue;
    }

    $content = file_get_contents($file);

    // Skip if it's a trait itself or already has WithAlert
    if (strpos($content, 'trait ') !== false && strpos($content, 'class ') === false) {
        // Just add to the trait if needed
        if (strpos($content, 'use App\Traits\WithAlert;') === false) {
            $content = str_replace("namespace App\Livewire\Utils;\n\n", "namespace App\Livewire\Utils;\n\nuse App\Traits\WithAlert;\n", $content);
            $content = preg_replace('/trait [^{]+{/', "$0\n    use WithAlert;\n", $content);
            file_put_contents($file, $content);
        }

        continue;
    }

    if (strpos($content, 'use App\Traits\WithAlert;') === false) {
        // Find namespace and add use statement
        $content = preg_replace('/namespace App\\\Livewire\\\([^;]+);\n\n/', "namespace App\\Livewire\\$1;\n\nuse App\Traits\WithAlert;\n", $content);
    }

    if (strpos($content, 'use WithAlert;') === false) {
        // Find class definition and add use statement inside
        $content = preg_replace('/class [^{]+{/', "$0\n    use WithAlert;\n", $content);
    }

    file_put_contents($file, $content);
    echo "Fixed $file\n";
}
