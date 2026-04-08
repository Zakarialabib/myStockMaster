<?php
$files = [
    'resources/views/livewire/adjustment/index.blade.php',
    'resources/views/livewire/quotations/index.blade.php',
    'resources/views/livewire/brands/index.blade.php',
    'resources/views/livewire/currency/index.blade.php',
    'resources/views/livewire/warehouses/index.blade.php',
    'resources/views/livewire/purchase/index.blade.php'
];

foreach ($files as $bladeFile) {
    if (file_exists($bladeFile)) {
        $content = file_get_contents($bladeFile);
        $content = str_replace('</x-slot>'."\n".'    </x-page-container>', '</div>'."\n".'    </x-page-container>', $content);
        $content = preg_replace('/<\/x-slot>\s*<\/x-page-container>/', "</div>\n    </x-page-container>", $content);
        file_put_contents($bladeFile, $content);
        echo "Fixed closing div in $bladeFile\n";
    }
}
