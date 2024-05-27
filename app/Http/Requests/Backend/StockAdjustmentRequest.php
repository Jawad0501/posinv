<?php

namespace App\Http\Requests\Backend;

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
            'person' => 'required|integer|exists:staff,id',
            'date' => 'required|date',
            'ingredient_id' => 'required|array',
            'ingredient_id.*' => 'integer',
            'quantity_amount' => 'required|array',
            'quantity_amount.*' => 'numeric',
            'consumption_status' => 'required|array',
            'consumption_status.*' => 'string',
            'note' => 'nullable|string',
        ];
    }
}
