<?php

namespace App\Http\Requests\Api\Backend;

use App\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            // 'order_id' => ['required', 'integer', 'exists:orders,id'],
            // 'use_rewards' => ['nullable'],
            // 'include_service_charge' => ['nullable'],
            // 'customer_special_discount' => ['nullable'],
            'customer_id' => ['nullable'],
            'payment_method' => ['required', 'string', 'in:'.PaymentMethod::CARD->value.','.PaymentMethod::CASH->value],
            'discount_amount' => ['required', 'numeric'],
            'discount_type'   => ['required', 'in:fixed,percentage'],
            'give_amount' => ['required', 'numeric'],
            'gift_card_amount' => ['nullable', 'numeric'],
            'change_amount' => ['nullable', 'numeric'],
            'due_amount' => ['nullable', 'numeric'],
            'change_returned' => ['nullable'],  
            'settle_advance' => ['nullable']
        ];
    }
}
