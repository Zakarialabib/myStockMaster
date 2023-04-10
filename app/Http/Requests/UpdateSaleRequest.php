<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'     => 'required|numeric',
            'code'            => 'string|max:255',
            'shipping_amount' => 'required|numeric',
            'total_amount'    => 'required|numeric',
            'paid_amount'     => 'required|numeric|max:'.$this->sale->total_amount,
            'status'          => 'required|string|max:255',
            'payment_method'  => 'required|string|max:255',
            'note'            => 'nullable|string|max:1000',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit_sales');
    }
}
