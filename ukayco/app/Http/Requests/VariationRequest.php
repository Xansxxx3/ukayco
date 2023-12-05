<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariationRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:product_categories,id'], // Ensure the category_id exists in the product_categories table.
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
