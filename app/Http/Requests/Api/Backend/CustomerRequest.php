<?php

namespace App\Http\Requests\Api\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        if (request()->isMethod('POST')) {
            $emailRule = 'nullable|email|string|max:50|unique:users,email';
            $phoneRule = 'required|string|max:50|unique:users,phone';
        } else {
            $emailRule = 'nullable|email|string|max:50|unique:users,email,'.$this->route('customer');
            $phoneRule = 'required|string|max:50|unique:users,phone,'.$this->route('customer');
        }

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => $emailRule,
            'phone' => $phoneRule,
            'delivery_address' => 'nullable|string',
            'discount' => 'nullable|string',
        ];
    }
}
