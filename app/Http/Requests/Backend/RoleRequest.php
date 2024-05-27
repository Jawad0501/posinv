<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $methodRule = request()->isMethod('POST') ? 'unique:roles,name' : 'unique:roles,name,'.$this->route('role')->id;

        return [
            'name' => 'required|string|max:255|'.$methodRule,
            'description' => 'nullable|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'integer',
        ];
    }
}
