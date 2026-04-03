<?php
$content = file_get_contents('app/Livewire/SaleReturn/Create.php');

$content = str_replace('use Livewire\Component;', "use App\Livewire\Forms\SaleReturnForm;\nuse Livewire\Component;", $content);

$content = preg_replace('/#\[Validate\([^\]]+\)\]\s*public \$customer_id;/s', 'public SaleReturnForm $form;', $content);

$props_to_remove = [
    'public $reference;',
    'public $tax_percentage;',
    'public $discount_percentage;',
    'public $shipping_amount;',
    'public $total_amount;',
    'public $paid_amount;',
    'public $status;',
    'public $payment_method;',
    'public $note;',
    'public $date;'
];

foreach ($props_to_remove as $prop) {
    $content = preg_replace('/#\[Validate\([^\]]+\)\]\s*' . preg_quote($prop, '/') . '/s', '', $content);
    $content = str_replace($prop, '', $content);
}

$vars = ['customer_id', 'reference', 'tax_percentage', 'discount_percentage', 'shipping_amount', 'total_amount', 'paid_amount', 'status', 'payment_method', 'note', 'date'];
foreach ($vars as $var) {
    $content = str_replace('$this->' . $var, '$this->form->' . $var, $content);
}

$content = str_replace('$this->validate();', '$this->form->validate();', $content);

file_put_contents('app/Livewire/SaleReturn/Create.php', $content);
