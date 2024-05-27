<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductionUnitRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'food_name' => ['required', 'integer', 'exists:food,id'],
            'variant_name' => ['required', 'integer', 'exists:variants,id'],
            'items' => ['required', 'array'],
            'items.*' => ['integer'],
            'products' => ['required', 'array'],
            'products.*' => ['string'],
            'qunatity' => ['required', 'array'],
            'qunatity.*' => ['integer'],
            'units' => ['required', 'array'],
            'units.*' => ['string'],
            'price' => ['required', 'array'],
            'price.*' => ['numeric'],
        ];
    }
}
