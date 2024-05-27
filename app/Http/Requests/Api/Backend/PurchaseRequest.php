<?php

namespace App\Http\Requests\Api\Backend;

use App\Models\Purchase;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'supplier' => ['required', 'integer', 'exists:suppliers,id'],
            'payment_type' => ['required', 'string', 'in:'.Purchase::PAYMENT_TYPE_CASH.','.Purchase::PAYMENT_TYPE_BANK.','.Purchase::PAYMENT_TYPE_DUE],
            'bank' => ['nullable', 'required_if:payment_type,==,'.Purchase::PAYMENT_TYPE_BANK, 'integer', 'exists:banks,id'],
            'purchase_date' => ['required', 'date'],
            'details' => ['nullable', 'string'],
            'ingredients' => ['required', 'array'],
            'ingredients.*.id' => ['required', 'integer', 'exists:ingredients,id'],
            'ingredients.*.expire_date' => ['required', 'date'],
            'ingredients.*.unit_price' => ['required', 'numeric'],
            'ingredients.*.quantity_amount' => ['required', 'numeric'],
            'total_amount' => ['required', 'numeric'],
            'shipping_charge' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'paid' => ['required', 'numeric'],
        ];
    }
}
