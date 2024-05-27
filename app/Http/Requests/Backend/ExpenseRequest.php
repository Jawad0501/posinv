<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'category' => 'required|integer|exists:expense_categories,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ];
    }
}
