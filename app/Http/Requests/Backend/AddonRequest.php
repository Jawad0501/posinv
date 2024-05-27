<?php

namespace App\Http\Requests\Backend;

use App\Models\Addon;
use Illuminate\Foundation\Http\FormRequest;

class AddonRequest extends FormRequest
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
            'parent_id' => ['nullable', 'integer', 'exists:addons,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'title' => ['nullable', 'string'],
        ];
    }

    /**
     * update or store a new addon
     */
    public function saved(Addon $addon = null)
    {
        $this->isMethod('POST') ? Addon::query()->create($this->validated()) : $addon->update($this->validated());

        return response()->json(['message' => $this->isMethod('POST') ? 'New addon added successfully' : 'Addon updated successfully']);
    }
}
