<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', request()->isMethod('POST') ? 'unique:categories,name' : 'unique:categories,name,'.$this->route('category')->id], // $this->route('category')->id
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:512'],
            'is_drinks' => ['nullable'],
        ];
    }
}
