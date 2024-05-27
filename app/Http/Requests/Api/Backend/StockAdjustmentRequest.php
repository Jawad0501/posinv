<?php

namespace App\Http\Requests\Api\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
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
            'person' => ['required', 'integer', 'exists:staff,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'ingredients' => ['required', 'array'],
            'ingredients.*.id' => ['required', 'integer', 'exists:ingredients,id'],
            'ingredients.*.quantity_amount' => ['required', 'integer'],
            'ingredients.*.consumption_status' => ['required', 'string'],
            'note' => ['string'],
        ];
    }
}
