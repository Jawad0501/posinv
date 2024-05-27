<?php

namespace App\Http\Livewire\Frontend\Pages;

use App\Enum\PaymentStatus;
use App\Mail\OrderConfirmationMail;
use App\Models\GiftCard;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentCallback extends Component
{
    public $payment;

    #[Url()]
    public $gift_card;

    public $is_api;

    public function mount($status, $trx_id)
    {
        // $this->payment = Payment::query()->with('order','order.orderDetails')->first();
        if ($this->gift_card) {
            $this->payment = GiftCard::query()->where('status', PaymentStatus::PENDING->value)->where('trx', $trx_id)->firstOrFail();
            if (! $this->payment) {
                return $this->redirect('/', true);
            }
            $this->payment->status = $status;
            $this->payment->save();
        } else {
            $this->payment = Payment::query()->with('order', 'order.orderDetails')->where('status', PaymentStatus::PENDING->value)->where('trx', $trx_id)->first();
            if (! $this->payment) {
                return $this->redirect('/', true);
            }
            $this->payment->status = $status;
            if (isset(request()['token']) && $status == PaymentStatus::SUCCESS->value) {
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $provider->getAccessToken();
                $response = $provider->capturePaymentOrder(request()['token']);
                if (! isset($response['status']) && $response['status'] != 'COMPLETED') {
                    $this->payment->status = PaymentStatus::CANCEL->value;
                }
            }
            $this->payment->save();
            Mail::to($this->payment->user?->email)->send(new OrderConfirmationMail($this->payment->order));
        }
    }

    #[Title('Payment')]
    public function render()
    {
        return view('livewire.frontend.pages.payment-callback');
    }
}
