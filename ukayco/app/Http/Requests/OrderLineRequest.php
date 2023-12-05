<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_item_id' => 'required|integer',
            'price' => 'required|numeric',
            'payment_method_id' => 'required|integer',
            'shipping_address_id' => 'required|integer',
        ];
    }
}
