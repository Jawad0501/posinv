<?php

namespace App\Http\Requests\Api\Backend;

use App\Enum\OrderType;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'order_type' => ['required', 'string', 'in:'.OrderType::DINEIN->value.','.OrderType::TAKEWAY->value.','.OrderType::DELIVERY->value],
            'customer' => ['nullable', 'required_if:order_type,'.OrderType::DELIVERY->value, 'integer', 'exists:users,id'],
            'rider' => ['nullable', 'integer', 'exists:users,id'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:food,id'],
            'items.*.order_details_id' => ['nullable', 'integer', 'exists:order_details,id'],
            'items.*.quantity' => ['required', 'integer'],
            'items.*.variant_id' => ['nullable', 'integer', 'exists:variants,id'],
            'items.*.addons' => ['nullable', 'array'],
            'items.*.addons.*.id' => ['nullable', 'integer', 'exists:addons,id'],
            'items.*.addons.*.quantity' => ['nullable', 'integer'],
            'discount' => ['nullable', 'numeric'],
            'tables' => ['nullable', 'array'],
            'tables.*.id' => ['nullable', 'integer', 'exists:tablelayouts,id'],
            'tables.*.person' => ['nullable', 'integer'],
        ];
    }
}
