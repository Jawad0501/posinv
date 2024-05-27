<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|string|max:255',
            'phone' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'id_card_front' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'id_card_back' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
            'status' => 'nullable|boolean',
            'advance_amount' => 'nullable|numeric'
        ];
    }
}
