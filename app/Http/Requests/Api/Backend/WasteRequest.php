<?php

namespace App\Http\Requests\Api\Backend;

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
            'person' => ['required', 'integer', 'exists:staff,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'foods' => ['required', 'array'],
            'foods.*.id' => ['required', 'integer', 'exists:food,id'],
            'foods.*.quantity' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
        ];
    }
}
