<?php
$files = [
    __DIR__ . '/app/Models/Cart.php' => "get: function (): float|int {",
    __DIR__ . '/app/Models/CartItem.php' => "get: function (): float|int {",
    __DIR__ . '/app/Models/Product.php' => "get: function (): ?float {",
];

foreach ($files as $file => $replace) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace("get: function () {", $replace, $content);
        file_put_contents($file, $content);
    }
}
echo "Done\n";
