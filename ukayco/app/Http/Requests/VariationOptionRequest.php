<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariationOptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'value' => ['required', 'string'],
            'variation_id' => ['required', 'integer', 'exists:variations,id'],
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}







