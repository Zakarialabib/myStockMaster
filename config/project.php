<?php

return [
    'date_format'               => 'Y-m-d',
    'time_format'               => 'H:i:s',
    'datetime_format'           => 'Y-m-d H:i:s',
    'flatpickr_date_format'     => 'Y-m-d',
    'flatpickr_time_format'     => 'H:i:S',
    'flatpickr_datetime_format' => 'Y-m-d H:i:S',
    'supported_languages'       => [
        [
            'title'      => 'French',
            'short_code' => 'fr',
        ],
    ],
    'pagination' => [
        'options' => [
            10,
            25,
            50,
            100,
        ],
    ],
    'invoiceLayout' => [
        'name' => 'Default',
        'header_text' => 'MyStock',
        'highlight_color' => '#000000',
        'footer_text' => '',
        'is_default' => 1,
        'invoice_heading_not_paid' => '',
        'invoice_heading_paid' => '',
        'show_city' => 1,
        'show_country' => 1,
        'show_payments' => 1,
        'show_customer' => 1,
        'show_shipping' => 1,
        'show_order_tax' => 1,
        'show_discount' => 1,
    ],
    
    'ref_no_prefixes' => [
        'purchase' => 'PO',
        'sell' => 'SL',
        'stock_adjustment' => 'SA',
        'sell_return' => 'SR',
        'sell_payment' => 'SP',
        'expense' => 'EP',
        'purchase_return' => 'PR',
        'purchase_payment' => 'PP',
        'quotation' => 'BL'
    ] 
];