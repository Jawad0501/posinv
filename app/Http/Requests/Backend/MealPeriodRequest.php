<?php

namespace App\Http\Requests\Backend;

use App\Models\MealPeriod;
use Illuminate\Foundation\Http\FormRequest;

class MealPeriodRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'], // $this->route('meal_period')->id
            'time_slot' => ['required', 'array'],
            'time_slot.start_time' => ['required', 'date_format:H:i'],
            'time_slot.end_time' => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * save or update meal period
     */
    public function saved(MealPeriod $mealPeriod = null)
    {
        $this->isMethod('POST') ? MealPeriod::query()->create($this->validated()) : $mealPeriod->update($this->validated());

        return response()->json(['message' => $this->isMethod('POST') ? 'New meal period added successfully' : 'Meal period updated successfully']);
    }
}
