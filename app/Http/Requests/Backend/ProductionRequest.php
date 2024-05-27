<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductionRequest extends FormRequest
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
            'production_unit' => 'required|integer|exists:production_units,id',
            'production_date' => 'required|date',
            'serving_unit' => 'required|integer',
            'expire_date' => 'required|date',
        ];
    }
}
