<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'total_person' => [$this->isMethod('POST') ? 'required' : 'nullable', 'numeric'],
            'expected_date' => [$this->isMethod('POST') ? 'required' : 'nullable'],
            'expected_time' => [$this->isMethod('POST') ? 'required' : 'nullable'],
            'contact_no' => [$this->isMethod('POST') ? 'required' : 'nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:8', 'max:20'],

            'name' => [$this->isMethod('PUT') ? 'required' : 'nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'string', 'max:255'],
            'occasion' => ['nullable', 'string', 'max:255'],
            'special_request' => ['nullable', 'string'],
        ];
    }
}
