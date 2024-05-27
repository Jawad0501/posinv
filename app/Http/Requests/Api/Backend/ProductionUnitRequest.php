<?php

namespace App\Http\Requests\Api\Backend;

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
            'food' => ['required', 'integer', 'exists:food,id'],
            'variant' => ['required', 'integer', 'exists:variants,id'],
            'items' => ['required', 'array'],
            'items.*.id' => ['nullable', 'integer', 'exists:production_unit_items,id'],
            'items.*.ingredient' => ['required', 'integer', 'exists:ingredients,id'],
            'items.*.quantity' => ['required', 'integer'],
            'items.*.unit' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric'],
        ];
    }
}
