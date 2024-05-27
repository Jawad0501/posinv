<?php

namespace App\Http\Requests\Backend;

use App\Http\Controllers\Frontend\PaymentController;
use App\Models\GiftCard;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class GiftCardRequest extends FormRequest
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
            'customer' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric'],
            // 'card_name'    => ['required','string'],
            // 'card_number'  => ['required','string'],
            // 'expiry_month' => ['required','integer'],
            // 'expiry_year'  => ['required','integer'],
            // 'cvc'          => ['required','integer'],
        ];
    }

    /**
     * complete giff card payment
     */
    public function saved()
    {
        $user = User::query()->find($this->customer);
        $trx_id = getTrx();
        $payment = new PaymentController;
        $response = $payment->stripe($this->amount, $trx_id, ['gift_card' => rand()]);

        if (isset($response->error)) {
            return response()->json(['message' => 'Something went wrong, please try again'], 300);
        }

        GiftCard::query()->create([
            'user_id' => $user->id,
            'amount' => $this->amount,
            'trx' => $trx_id,
            'btc_wallet' => $response?->session?->id,
        ]);

        $param = $this->is('api*') ? 'redirect' : 'print_url';

        return response()->json([
            $param => $response->session->url,
            'message' => 'Please complete your payument.',
        ]);
    }
}
