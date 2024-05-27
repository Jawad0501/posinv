<?php

namespace App\Http\Requests\Backend\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'title' => 'required|string|in:Ms,Mrs,Miss,Mr',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:11',
            'gender' => 'required|string|in:M,F',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'post_code' => 'required|string|max:255',
            // 'country' => 'required|string|max:255',
        ];
    }
}
