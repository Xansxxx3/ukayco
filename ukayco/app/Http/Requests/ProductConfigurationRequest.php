<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductConfigurationRequest extends FormRequest
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
            'product_item_id' => 'required|exists:product_items,id',
            'variation_option_id' => 'required|exists:variation_options,id',
        ];
    }
}
