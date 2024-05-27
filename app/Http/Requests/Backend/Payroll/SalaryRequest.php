<?php

namespace App\Http\Requests\Backend\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
            'employee' => 'required|string|max:255',
            'earning_rate' => 'required|string|max:255',
            'unit_per_day' => 'required|integer',
            'unit_per_week' => 'required|integer',
            'annual_salary' => 'required|numeric',
            'effective_from' => 'required|date',
        ];
    }
}
