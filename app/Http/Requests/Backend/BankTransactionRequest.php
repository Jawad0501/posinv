<?php

namespace App\Http\Requests\Backend;

use App\Models\BankTransaction;
use Illuminate\Foundation\Http\FormRequest;

class BankTransactionRequest extends FormRequest
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
            'bank' => ['required', 'integer', 'exists:banks,id'],
            'withdraw_deposite_id' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:'.BankTransaction::TYPE_CREADIT.','.BankTransaction::TYPE_DEBIT],
            'decsription' => ['nullable', 'string'],
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
