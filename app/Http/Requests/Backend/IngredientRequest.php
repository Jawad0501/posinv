<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class IngredientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['nullable', 'numeric'],
            'category' => ['required', 'integer', 'exists:ingredient_categories,id'],
            'alert_qty' => ['required', 'integer'],
            'unit' => ['required', 'integer', 'exists:ingredient_units,id'],
            'code' => ['nullable', 'string', 'max:255'],
        ];
    }
}
