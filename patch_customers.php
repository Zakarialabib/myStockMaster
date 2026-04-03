<?php
$content = file_get_contents('app/Livewire/Reports/CustomersReport.php');
$content = str_replace('public $customers;', '', $content);
$content = preg_replace('/\$this->customers = [^;]+;/', '', $content);
$content = str_replace('public function render()', "#[Computed]\n    public function customers()\n    {\n        return Customer::select(['id', 'name'])->get();\n    }\n\n    public function render()", $content);
file_put_contents('app/Livewire/Reports/CustomersReport.php', $content);
