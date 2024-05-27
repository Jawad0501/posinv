<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'app_title' => 'string|string|max:255',
            'email' => 'string|string|max:255',
            'phone' => 'string|string|max:255',
            'image_resize' => 'string|string|max:5',
            'dark_logo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif,bmp,webp',
            'light_logo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif,bmp,webp',
            'favicon' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif,bmp,webp',
            'invoice_logo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif,bmp,webp',
            'default_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif,bmp,webp',
            'timezone' => 'required|string|max:255',
            'date_format' => 'required|string|max:20',
            'currency_code' => 'required|string|max:20',
            'currency_symbol' => 'required|string|max:20',
            'currency_position' => 'required|string|max:15',
            'service_charge' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'address' => 'required|string',
            'copyright' => 'required|string',
            'restaurant_description' => 'nullable|string',
            'opening_purchase_stock' => 'nullable|numeric',
            'opening_sale_stock' => 'nullable|numeric',
            'cash_in_hand' => 'nullable|numeric',
            'total_sit_capacity' => 'nullable|integer',
            // 'grace_period' => 'required|numeric',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            // 'verify_by' => 'required|string|in:phone,email',

            // 'mail' => 'nullable|array',
            // 'mail.mailer' => 'nullable|string|max:10',
            // 'mail.encryption' => 'nullable|string|max:3',
            // 'mail.port' => 'nullable|integer',
            // 'mail.host' => 'nullable|string',
            // 'mail.username' => 'nullable|string',
            // 'mail.password' => 'nullable|string',
            // 'mail.from_address' => 'nullable|email|string|max:50',
            // 'mail.from_name' => 'nullable|string|string|max:50',

            // 'payment' => 'nullable|array',
            // 'payment.stripe' => 'nullable|array',
            // 'payment.stripe.key' => 'nullable|string',
            // 'payment.stripe.secret' => 'nullable|string',
            // 'payment.stripe.enable' => 'nullable|boolean',

            // 'payment.paypal' => 'nullable|array',
            // 'payment.paypal.client_id' => 'nullable|string',
            // 'payment.paypal.client_secret' => 'nullable|string',
            // 'payment.paypal.enable' => 'nullable|boolean',

            // 'oauth' => 'nullable|array',
            // 'oauth.facebook' => 'nullable|array',
            // 'oauth.facebook.enable' => 'nullable|boolean',
            // 'oauth.facebook.client_id' => 'nullable|string',
            // 'oauth.facebook.client_secret' => 'nullable|string',
            // 'oauth.facebook.redirect' => 'nullable|url',

            // 'oauth.google' => 'nullable|array',
            // 'oauth.google.enable' => 'nullable|boolean',
            // 'oauth.google.client_id' => 'nullable|string',
            // 'oauth.google.client_secret' => 'nullable|string',
            // 'oauth.google.redirect' => 'nullable|url',

            // 'sms' => 'nullable|array',
            // 'sms.twilio' => 'nullable|array',
            // 'sms.twilio.sid' => 'nullable|string',
            // 'sms.twilio.auth_token' => 'nullable|string',
            // 'sms.twilio.verify_sid' => 'nullable|string',
            // 'sms.twilio.enable' => 'nullable|boolean',

            // 'hero_section_content' => ['nullable', 'array'],
            // 'hero_section_content.heading' => ['nullable', 'string', 'max:255'],
            // 'hero_section_content.description' => ['nullable', 'string'],
            // 'hero_section_content.image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:1024'],
            // 'contact_content' => ['nullable', 'array'],
            // 'contact_content.address' => ['nullable', 'string', 'max:255'],
            // 'contact_content.address_title' => ['nullable', 'string', 'max:255'],
            // 'contact_content.phone' => ['nullable', 'string', 'max:255'],
            // 'contact_content.phone_title' => ['nullable', 'string', 'max:255'],
            // 'contact_content.email' => ['nullable', 'string', 'max:255'],
            // 'contact_content.email_title' => ['nullable', 'string', 'max:255'],
            // 'contact_content.support' => ['nullable', 'string', 'max:255'],
            // 'contact_content.support_title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
