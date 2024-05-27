<?php

namespace App\Http\Requests\Backend;

use App\Models\IngredientUnit;
use Illuminate\Foundation\Http\FormRequest;

class IngredientUnitRequest extends FormRequest
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
            'description' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ];
    }

    /**
     * update or store ingredient unit
     */
    public function saved(IngredientUnit $ingredientUnit = null)
    {
        $this->isMethod('POST') ? IngredientUnit::query()->create($this->validated()) : $ingredientUnit->update($this->validated());

        return response()->json(['message' => $this->isMethod('POST') ? 'Ingredient unit created successfully' : 'Ingredient unit updated successfully']);
    }
}
