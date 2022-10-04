<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSetting extends Model
{

    protected $fillable = [
        'note_customer', 'show_note', 'show_barcode', 'show_discount', 'show_customer',
         'show_email','show_phone','show_address','show_tax_number',
    ];

    protected $casts = [
        'show_note' => 'integer',
        'show_barcode' => 'integer',
        'show_discount' => 'integer',
        'show_customer' => 'integer',
        'show_email' => 'integer',
        'show_phone' => 'integer',
        'show_address' => 'integer',
    ];


}
