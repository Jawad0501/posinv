<?php

namespace App\Http\Requests\Frontend;

use App\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'discount' => ['required', 'numeric'],
            'address_book' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
            'shipping_method' => ['required', 'string', 'in:pickup,delivery'],
            'payment_method' => ['required', 'string', 'in:'.PaymentMethod::CASH->value.','.PaymentMethod::CARD->value.','.PaymentMethod::STRIPE->value.','.PaymentMethod::PAYPAL->value],
            'use_reward' => ['nullable'],
            'items' => ['required', 'array'],
            'items.*.slug' => ['required', 'string', 'exists:food,slug'],
            'items.*.quantity' => ['required', 'integer'],
            'items.*.variant_id' => ['nullable', 'integer', 'exists:variants,id'],
            'items.*.addons' => ['nullable', 'array'],
            'items.*.addons.*.id' => ['nullable', 'integer', 'exists:addons,id'],
            'items.*.addons.*.quantity' => ['nullable', 'integer'],
        ];
    }
}
