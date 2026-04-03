<?php
$content = file_get_contents('app/Livewire/Quotations/Create.php');

$content = str_replace('use Livewire\Component;', "use App\Livewire\Forms\QuotationForm;\nuse Livewire\Component;", $content);

$content = preg_replace('/#\[Validate\([^\]]+\)\]\s*public \$customer_id;/s', 'public QuotationForm $form;', $content);

$props_to_remove = [
    'public $warehouse_id;',
    'public $total_amount;',
    'public $shipping_amount;',
    'public $note;',
    'public $status;',
    'public $date;',
    'public $tax_percentage;',
    'public $discount_percentage;'
];

foreach ($props_to_remove as $prop) {
    $content = preg_replace('/#\[Validate\([^\]]+\)\]\s*' . preg_quote($prop, '/') . '/s', '', $content);
    $content = str_replace($prop, '', $content);
}

$vars = ['customer_id', 'warehouse_id', 'total_amount', 'shipping_amount', 'note', 'status', 'date', 'tax_percentage', 'discount_percentage'];
foreach ($vars as $var) {
    $content = str_replace('$this->' . $var, '$this->form->' . $var, $content);
}

$content = str_replace('$this->validate();', '$this->form->validate();', $content);
$content = str_replace('updatedWarehouseId', 'updatedFormWarehouseId', $content);

file_put_contents('app/Livewire/Quotations/Create.php', $content);
