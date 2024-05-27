<?php

namespace App\Http\Requests\Backend;

use App\Http\Controllers\Api\Backend\Food\MenuController;
use App\Models\Food;
use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
        $food = new MenuController;

        return [
            'categories' => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', $this->isMethod('POST') ? 'unique:food,name' : "unique:food,name,{$this->route('menu')->id}"],
            'price' => ['required', 'numeric'],
            'processing_time' => ['nullable', 'integer'],
            'tax_vat' => ['required', 'numeric'],
            'calorie' => ['nullable', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:8192'],
            'description' => ['nullable', 'string'],

            'meal_periods' => ['nullable', 'array'],
            'meal_periods.*' => ['integer', 'exists:meal_periods,id'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer', 'exists:addons,id'],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['integer', 'exists:allergies,id'],
            // 'ingredient_name' => [$food->categoryCheck() > 0 ? 'required' : 'nullable', 'integer', 'exists:ingredients,id'],

            'size' => ['nullable', 'string', 'max:255'],
            'token' => ['nullable', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'string', 'max:255'],
            'gtin' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Save or update food
     */
    public function saved(Food $food = null)
    {
        $input = $this->validated();
        $input['ingredient_id'] = $this->ingredient_name;
        $image = $this->isMethod('PUT') ? $food->image : null;
        $input['image'] = $this->hasFile('image') ? file_upload($this->image, 'food', $image, 800, 600) : $image;
        $input['online_item_visibility'] = $this->has('online_item_visibility');
        $input['sellable'] = $this->has('sellable');
        $input['processing_time'] = ! empty($this->processing_time) ? $this->processing_time : null;

        if ($this->isMethod('POST')) {
            $food = Food::query()->create($input);
        } else {
            $food->update($input);
        }

        if (! empty($this->size)) {
            $variant = $food->variants()->where('name', $this->size)->first();
            $variantData = ['name' => $this->size, 'price' => $this->price];
            $variant ? $variant->update($variantData) : $food->variants()->updateOrCreate($variantData);
        }

        $food->categories()->sync($this->input('categories'));
        $food->mealPeriods()->sync($this->input('meal_periods'));
        $food->addons()->sync($this->input('addons'));
        $food->allergies()->sync($this->input('allergies'));

        return $food;
    }
}
