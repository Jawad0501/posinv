<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
        $methodRule = request()->isMethod('POST') ? 'required' : 'nullable';

        return [
            'role' => 'required|integer|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required_without:phone',
            'phone' => 'required_without:email|max:50',
            'address' => 'required|string|max:255',
            'image' => "$methodRule|image|mimes:jpg,png,bmp,jpeg,webp|max:1024",
            'password' => "$methodRule|min:8|confirmed",
            'status' => 'required|boolean',
        ];
    }
}
