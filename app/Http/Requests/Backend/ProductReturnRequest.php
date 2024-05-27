<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductReturnRequest extends FormRequest
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
            'vendor' => ['required', 'string', 'in:supplier,customer'],
            'supplier' => ['required_if:vendor,supplier', 'integer'],
            'ref_number' => ['required_if:vendor,supplier', 'string'],
            'customer' => ['required_if:vendor,customer', 'integer'],
            'invoice_no' => ['required_if:vendor,customer', 'string'],
            'return_date' => ['required', 'date'],
            'payment_type' => ['required', 'string'],
            'bank' => ['nullable', 'required_if:payment_type,==,bank payment', 'integer', 'exists:banks,id'],   
            'details' => ['nullable', 'string'],
            'ingredient_id' => ['required', 'array'],
            'ingredient_id.*' => ['integer', 'exists:ingredients,id'],
            'unit_price' => ['required', 'array'],
            'unit_price.*' => ['required', 'numeric'],
            'quantity_amount' => ['required', 'array'],
            'quantity_amount.*' => ['required', 'numeric'],
            'total_amount' => ['required', 'numeric'],
            'shipping_charge' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'paid' => ['required', 'numeric'],
            'due' => ['required', 'numeric'],
        ];
    }
}
