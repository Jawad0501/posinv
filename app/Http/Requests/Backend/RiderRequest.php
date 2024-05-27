<?php

namespace App\Http\Requests\Backend;

use App\Models\User;
use App\Rules\LocationVerifyRule;
use Illuminate\Foundation\Http\FormRequest;

class RiderRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'string', 'max:50', $this->isMethod('POST') ? 'unique:users,email' : 'unique:users,email,'.$this->route('rider')->id],
            'phone' => ['nullable', 'string', 'max:50', $this->isMethod('POST') ? 'unique:users,phone' : 'unique:users,phone,'.$this->route('rider')->id],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'min:8', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255', new LocationVerifyRule],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:2048'],
        ];
    }

    /**
     * saved or update
     */
    public function saved(User $user = null)
    {
        $input = $this->validated();
        $input['role'] = 'Rider';
        $input['address_book'] = [['type' => 'Home', 'location' => $this->address]];
        if ($this->has('password')) {
            $input['password'] = bcrypt($this->password);
        }
        $image = $this->isMethod('PUT') ? $user->image : null;
        $input['image'] = $this->hasFile('image') ? file_upload($this->image, 'customer', $image) : $image;

        $this->isMethod('POST') ? User::create($input) : $user->update($input);

        return response()->json(['message' => $this->isMethod('PUT') ? 'Rider updated successfully' : 'Rider added successfully']);
    }
}
