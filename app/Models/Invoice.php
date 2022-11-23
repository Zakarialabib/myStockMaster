<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const SALE_TYPE = 1;

    const POS_TYPE = 2;

    const PURCHASE_TYPE = 3;

    const RETURN_TYPE = 4;

    const QUOTATION_TYPE = 5;

    const PREVIEW_ACTION = 1;

    const DOWNLOAD_ACTION = 2;

    const EMAIL_ACTION = 3;
}
