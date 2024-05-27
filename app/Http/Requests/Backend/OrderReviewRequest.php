<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class OrderReviewRequest extends FormRequest
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
            'customer' => 'required|integer|exists:users,id',
            'menu_food' => 'required|integer|exists:food,id',
            'rating' => 'required|integer',
            'comment' => 'required|string',
            'status' => 'required|boolean',
        ];
    }
}
