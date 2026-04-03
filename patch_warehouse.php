<?php
$content = file_get_contents('app/Livewire/Reports/WarehouseReport.php');
$content = str_replace('public $warehouses;', '', $content);
$content = preg_replace('/\$this->warehouses = [^;]+;/', '', $content);
$content = str_replace('public function render()', "#[Computed]\n    public function warehouses()\n    {\n        return Warehouse::select(['id', 'name'])->get();\n    }\n\n    public function render()", $content);
file_put_contents('app/Livewire/Reports/WarehouseReport.php', $content);
