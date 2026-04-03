<?php
$content = file_get_contents('app/Livewire/Reports/SuppliersReport.php');
$content = str_replace('public $suppliers;', '', $content);
$content = preg_replace('/\$this->suppliers = [^;]+;/', '', $content);
$content = str_replace('public function render()', "#[Computed]\n    public function suppliers()\n    {\n        return Supplier::select(['id', 'name'])->get();\n    }\n\n    public function render()", $content);
file_put_contents('app/Livewire/Reports/SuppliersReport.php', $content);
