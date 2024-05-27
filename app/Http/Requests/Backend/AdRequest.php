<?php

namespace App\Http\Requests\Backend;

use App\Models\Ad;
use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:self,blank'],
            'link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,bmp', 'max:255'],
        ];
    }

    public function saved(Ad $ad = null)
    {
        $input = $this->validated();
        $image = $this->isMethod('PUT') ? $ad->image : null;
        $input['image'] = $this->hasFile('image') ? file_upload($this->image, 'customer', $image) : $image;

        $this->isMethod('POST') ? Ad::query()->create($input) : $ad->update($input);

        return response()->json($this->isMethod('POST') ? 'Ad added successfully' : 'Ad updated successfully');
    }
}
