<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:products,code,' . $this->product->id],
            'barcode_symbology' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'cost' => ['required', 'numeric', 'max:2147483647'],
            'price' => ['required', 'numeric', 'max:2147483647'],
            'stock_alert' => ['required', 'integer', 'min:0'],
            'order_tax' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tax_type' => ['nullable', 'integer'],
            'note' => ['nullable', 'string', 'max:1000'],
            'category_id' => ['required', 'integer']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit_products');
    }
}
