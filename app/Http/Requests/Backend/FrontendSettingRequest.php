<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class FrontendSettingRequest extends FormRequest
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
            'hero_section_content' => ['nullable', 'array'],
            'hero_section_content.heading' => ['nullable', 'string', 'max:255'],
            'hero_section_content.description' => ['nullable', 'string'],
            'hero_section_content.image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:1024'],
            'contact_content' => ['nullable', 'array'],
            'contact_content.address' => ['nullable', 'string', 'max:255'],
            'contact_content.address_title' => ['nullable', 'string', 'max:255'],
            'contact_content.phone' => ['nullable', 'string', 'max:255'],
            'contact_content.phone_title' => ['nullable', 'string', 'max:255'],
            'contact_content.email' => ['nullable', 'string', 'max:255'],
            'contact_content.email_title' => ['nullable', 'string', 'max:255'],
            'contact_content.support' => ['nullable', 'string', 'max:255'],
            'contact_content.support_title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
