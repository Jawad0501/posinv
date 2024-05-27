<?php

namespace App\Http\Requests\Backend;

use App\Models\User;
use App\Rules\LocationVerifyRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CustomerRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'string', 'max:50', Rule::unique(User::class)->ignore($this->route('customer')->id ?? null)],
            'phone' => ['required', 'string', 'max:50', Rule::unique(User::class)->ignore($this->route('customer')->id ?? null)],
            'password' => [$this->isMethod('POST') && Request::route()->getName() != 'pos.customer.store' ? 'required' : 'nullable', 'min:8'],
            // removed LocationVerifyRule
            'address' => ['nullable', 'string', 'max:255'],
            'delivery_address' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'date_of_anniversary' => ['nullable', 'date'],
            'discount' => ['nullable', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:2048'],
        ];
    }

    /**
     * saved or update
     */
    public function saved(User $user = null)
    {
        $input = $this->validated();
        $input['address_book'] = [['type' => 'Home', 'location' => $this->address ?? $this->delivery_address]];
        if ($this->has('password')) {
            $input['password'] = bcrypt($this->password);
        }
        else{
            $input['password'] = bcrypt(rand()); 
        }
        if ($this->isMethod('POST')) {
            $input['email_verified_at'] = now();
            $input['remember_token'] = Str::random(10);
        }
        $image = $this->isMethod('PUT') ? $user->image : null;
        $input['image'] = $this->hasFile('image') ? file_upload($this->image, 'customer', $image) : $image;

        if ($this->isMethod('POST')) {
            $user = User::create($input);
            $response[] = [
                'id' => $user->id,
                'message' => 'Customer added successfully',
            ];
        } else {
            $user->update($input);
            $response['message'] = 'Customer updated successfully';
        }

        return response()->json($response);
    }
}
