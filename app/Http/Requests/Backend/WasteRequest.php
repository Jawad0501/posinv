<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class WasteRequest extends FormRequest
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
            'food_id' => 'required|array',
            'food_id.*' => 'integer|exists:food,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric',
            'total' => 'required|array',
            'total.*' => 'numeric',
            'note' => 'nullable|string',
        ];
    }
}
