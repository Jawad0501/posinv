<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'staff' => ['required', 'integer', 'exists:users,id'],
            'date' => ['required', 'string', 'max:255'],
            'check_in_time' => ['required', 'date_format:H:i:s'],
            'check_out_time' => ['nullable', 'date_format:H:i:s', 'after:check_in_time'],
        ];
    }
}
