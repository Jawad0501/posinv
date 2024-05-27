<?php

use App\Enum\PaymentStatus;
use App\Enum\ReservationStatus;
use App\Mail\VerificationNotificationMail;
use App\Models\User;
use App\Models\Xero;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\OAuth2\Client\Provider\GenericProvider;
use Twilio\Rest\Client;

if (! function_exists('settings')) {
    /**
     * settings
     */
    function settings()
    {
        $settings = Cache::get('settings');
        if(!$settings) {
            $settings = DB::table('settings')->get(['key', 'value']);
            Cache::put('settings', $settings);
        }
        return $settings;
    }
}

if (! function_exists('setting')) {
    /**
     * description
     */
    function setting($key, $default = null)
    {
        if(!Cache::has('settings')) {
            settings();
        }

        $setting  = Cache::get('settings')->where('key', $key)->first();

        return $setting->value ?? $default;
    }
}

if (! function_exists('setting_json')) {
    /**
     * description
     */
    function setting_json($key, $field = null)
    {
        $setting = json_decode(setting($key));

        return $setting->{$field} ?? null;
    }
}

if (! function_exists('check_permission')) {

    /**
     * check_permission
     *
     * @param  mixed  $permission
     */
    function check_permission($permission)
    {
        return Gate::authorize($permission) ? true : false;
    }
}

if (! function_exists('isStaffAuthenticated')) {

    /**
     * isStaffAuthenticated
     *
     * @param  mixed  $permission
     */
    function isStaffAuthenticated()
    {
        $slug = auth('staff')->user()->role->slug;
        if ($slug == 'admin') {
            return redirect(RouteServiceProvider::ADMIN);
        } elseif ($slug == 'kitchen') {
            return redirect(RouteServiceProvider::KITCHEN);
        } elseif ($slug == 'cashier') {
            return redirect(RouteServiceProvider::CASHIER);
        }
    }
}

if (! function_exists('isVerified')) {

    /**
     * isStaffAuthenticated
     *
     * @param  mixed  $permission
     * @return void
     */
    function isVerified()
    {
        $slug = auth()->user()->isVerified;
        if ($slug == '1') {
            return redirect('/');
        } elseif ($slug == '0') {
            return redirect('')->route('phone.verify');
        }
    }
}

if (! function_exists('generate_slug')) {
    /**
     * function generate unique slug
     *
     * @param  mixed  $slug
     * @return string
     */
    function generate_slug($slug = null)
    {
        return $slug != null ? Str::slug($slug) : Str::random();
    }
}

if (! function_exists('generate_invoice')) {
    /**
     * function generate unique invoice
     *
     * @param  mixed  $invoice
     * @return string
     */
    function generate_invoice($id, $prefixOnly = false)
    {
        return $prefixOnly ? 'KL' : 'KL'.sprintf('%s%05s', '', $id);
    }
}

if (! function_exists('file_upload')) {
    function file_upload($file, $folder, $current = null, $width = null, $height = null)
    {
        $filename = generate_slug().'.'.$file->getClientOriginalExtension();

        if ($current != null) {
            delete_uploaded_file($current);
        }

        if (! Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        if ($width === null && $height === null) {
            return $file->storeAs($folder, $filename, 'public');
        }

        \Intervention\Image\Facades\Image::make($file)->resize($width, $height, fn ($constraint) => $constraint->upsize())->save(storage_path("app/public/$folder/$filename"));

        return "$folder/$filename";
    }
}

if (! function_exists('uploaded_file')) {
    function uploaded_file($file): string
    {
        if ($file != null && Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->url($file);
        }

        return Storage::disk('public')->url(setting('default_image'));
    }
}

if (! function_exists('delete_uploaded_file')) {
    function delete_uploaded_file($file): bool
    {
        if ($file != null && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }

        return true;
    }
}

if (! function_exists('datatable_status')) {
    /**
     * datatable_status
     */
    function datatable_status($value, $trueVal, $falseValue)
    {
        return $value ? $trueVal : $falseValue;
    }
}

if (! function_exists('convert_amount')) {
    /**
     * convert_amount
     */
    function convert_amount($amount)
    {
        if (setting('currency_position') == 'Before Amount') {
            return setting('currency_symbol').number_format($amount, 2, '.', ',');
        }

        return number_format($amount, 2, '.', ',').setting('currency_symbol');
    }
}

/**
 * This function formats a number and returns them in specified format
 */
function num_f($input_number, $add_symbol = false, $business_details = null, $is_quantity = false)
{
    $thousand_separator = ! empty($business_details) ? $business_details->thousand_separator : session('currency')['thousand_separator'];
    $decimal_separator = ! empty($business_details) ? $business_details->decimal_separator : session('currency')['decimal_separator'];

    $currency_precision = ! empty($business_details) ? $business_details->currency_precision : session('business.currency_precision', 2);

    if ($is_quantity) {
        $currency_precision = ! empty($business_details) ? $business_details->quantity_precision : session('business.quantity_precision', 2);
    }

    $formatted = number_format($input_number, $currency_precision, $decimal_separator, $thousand_separator);

    if ($add_symbol) {
        $currency_symbol_placement = ! empty($business_details) ? $business_details->currency_symbol_placement : session('business.currency_symbol_placement');
        $symbol = ! empty($business_details) ? $business_details->currency_symbol : session('currency')['symbol'];

        if ($currency_symbol_placement == 'after') {
            $formatted = $formatted.' '.$symbol;
        } else {
            $formatted = $symbol.' '.$formatted;
        }
    }

    return $formatted;
}

if (! function_exists('format_date')) {
    /**
     * format_date
     */
    function format_date($date = null, $isTime = false)
    {
        $date = $date != null ? $date : date('Y-m-d');

        if ($isTime) {
            return date(setting('date_format').' g:i A', strtotime($date));
        }

        return date(setting('date_format'), strtotime($date));
    }
}

if (! function_exists('format_time')) {
    /**
     * format_date
     */
    function format_time($time)
    {
        return date('h:i:s A', strtotime($time));
    }
}

if (! function_exists('is_kitchen_panel')) {
    /**
     * is_kitchen_panel
     */
    function is_kitchen_panel()
    {
        return Request::is('staff/kitchen/panel') ? true : false;
    }
}

if (! function_exists('setXeroToken')) {
    /**
     * Set Xero Token
     */
    function setXeroToken($token, $expires, $tenantId, $refreshToken, $idToken)
    {
        $xero = Xero::where('staff_id', auth()->id())->first();
        $token = [
            'staff_id' => auth()->id(),
            'token' => $token,
            'expires' => $expires,
            'tenant_id' => $tenantId,
            'refresh_token' => $refreshToken,
            'id_token' => $idToken,
        ];

        return $xero ? $xero->update($token) : Xero::create($token);
    }
}

if (! function_exists('xeroProvider')) {
    /**
     * Xero Provider
     */
    function xeroProvider()
    {
        $provider = new GenericProvider([
            'clientId' => config('services.xero.client_id'),
            'clientSecret' => config('services.xero.client_secret'),
            'redirectUri' => config('services.xero.redirect'),
            'urlAuthorize' => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken' => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation',
        ]);

        return $provider;
    }
}

if (! function_exists('time_format')) {
    /**
     * function customer unique token
     */
    function time_format()
    {
        return 'H:i:s';
    }
}

if (! function_exists('attendance_stay')) {
    /**
     * function customer unique token
     */
    function attendance_stay($check_in_time, $check_out_time)
    {
        $in = Carbon::createFromFormat(time_format(), $check_in_time);
        $out = Carbon::createFromFormat(time_format(), $check_out_time);

        $diff = $in->diffAsCarbonInterval($out);

        $hours = $diff->hours <= 9 ? "0$diff->hours" : $diff->hours;
        $minutes = $diff->minutes <= 9 ? "0$diff->minutes" : $diff->minutes;
        $seconds = $diff->seconds <= 9 ? "0$diff->seconds" : $diff->seconds;

        return "$hours:$minutes:$seconds";
    }
}

if (! function_exists('subtotal_time')) {
    /**
     * function customer unique token
     */
    function subtotal_time($arr)
    {
        $total = 0;
        foreach ($arr as $element) {
            $temp = explode(':', $element);
            if (isset($temp[0]) && isset($temp[1]) && isset($temp[2])) {
                $total += (int) $temp[0] * 3600;
                $total += (int) $temp[1] * 60;
                $total += (int) $temp[2];
            }
        }

        // Format the seconds back into HH:MM:SS
        $formatted = sprintf('%02d:%02d:%02d', ($total / 3600), ($total / 60 % 60), $total % 60);

        return $formatted;
    }
}

if (! function_exists('isAdminUrlRequest')) {
    function isAdminUrlRequest()
    {
        return request()->is('staff*');
    }
}

if (! function_exists('sessionAlert')) {
    function sessionAlert($message, $type = 'success')
    {
        session()->flash('alert', (object) [
            'type' => $type,
            'message' => $message,
        ]);

        return true;
    }
}

if (! function_exists('sendVerificationNotification')) {
    function sendVerificationNotification(User $user, $data = [])
    {
        if ($user->verify_field == 'email' || (bool) setting('verify_by') == 'email') {
            $otpCode = rand(1111, 9999);

            $data['otp_code'] = $otpCode;
            $data['verify_field'] = 'email';

            Mail::to($user->email)->send(new VerificationNotificationMail($user, $otpCode));
        } else {
            $token = config('services.twilio.auth_token');
            $twilio_sid = config('services.twilio.sid');
            $twilio_verify_sid = config('services.twilio.verify_sid');

            $data['verify_field'] = 'phone';

            $twilio = new Client($twilio_sid, $token);
            $twilio->verify->v2->services($twilio_verify_sid)->verifications->create($user->phone, 'sms');
        }
        $user->update($data);

        return true;
    }
}

if (! function_exists('maskData')) {
    function maskData($data)
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            // return preg_replace('/\B[^@.]/', '*', $data);
            return Str::mask($data, '*', 2, 5);
        } else {
            try {
                return substr($data, 0, 2).str_repeat('*', (strlen($data) - 4)).substr($data, -2);
            } catch (\Throwable $th) {
                return null;
            }
        }
    }
}

if (! function_exists('cartData')) {
    /**
     * cart data
     *
     * @return array
     */
    function cartData()
    {
        return session()->has('cart') ? session()->get('cart') : [];
    }
}

if (! function_exists('cartDataGrandTotal')) {
    /**
     * cart data grand total
     */
    function cartDataGrandTotal($items)
    {
        $subTotal = 0;

        foreach ($items as $item) {
            $menu = DB::table('food')->where('slug', $item['slug'])->first(['price', 'tax_vat']);
            $variantPrice = DB::table('variants')->where('id', $item['variant_id'])->value('price');

            if ($menu !== null) {
                $price = $variantPrice ?? $menu->price;
                $total = $price * $item['quantity'];
                $total_vat = $menu->tax_vat * $item['quantity'];
                $tax_vat = ($total / 100) * $total_vat;
                $subTotal += ($total + $tax_vat);

                foreach ($item['addons'] as $itemAddon) {
                    $addonPrice = DB::table('addons')->where('id', $itemAddon['quantity'])->value('price');
                    if ($addonPrice !== null) {
                        $subTotal += $addonPrice * $itemAddon['quantity'];
                    }
                }
            }
        }

        return $subTotal;
    }
}

if (! function_exists('getTrx')) {
    function getTrx($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (! function_exists('reservationTimeSlots')) {
    function reservationTimeSlots()
    {
        $grace_period = (int) setting('grace_period');
        $start = strtotime(setting('opening_time'));
        $end = strtotime(setting('closing_time'));
        $range = [date('h:i A', strtotime(setting('opening_time')))];

        while ($start !== $end) {
            $start = strtotime("+$grace_period hours", $start);
            $range[] = date('h:i A', $start);
        }

        return $range;
    }
}

if (! function_exists('checkReservationAvailability')) {
    function checkReservationAvailability($expected_date, $time, $total_person)
    {
        $total_books = 0;
        DB::table('reservations')
            ->where('expected_date', date('Y-m-d', strtotime($expected_date)))
            ->where('expected_time', date('H:i:s', strtotime($time)))
            ->orderBy('id')
            ->chunk(100, function ($reservations) use (&$total_books) {
                foreach ($reservations as $reservation) {
                    if ($reservation->status !== ReservationStatus::CANCEL->value) {
                        if ($reservation->status == ReservationStatus::HOLD->value) {
                            $to = now()->createFromFormat('Y-m-d H:i:s', $reservation->created_at);
                            $from = now();
                            $diffInMinutes = $to->diffInMinutes($from);
                            if ($diffInMinutes < 5) {
                                $total_books += $reservation->total_person;
                            }
                        } else {
                            $total_books += $reservation->total_person;
                        }
                    }
                }
            });

        $available_capacity = (int) setting('total_sit_capacity') - $total_books;
        $avaialbe = $total_books < (int) setting('total_sit_capacity') && $total_person <= $available_capacity;

        return $avaialbe;
    }
}

if (! function_exists('orderAvailableTime')) {
    function orderAvailableTime($created_at, int $processing_time)
    {
        $to = now()->createFromFormat('Y-m-d H:i:s', $created_at);
        $from = now();
        $processtime = now()->parse(gmdate('H:i:s', $processing_time * 60));
        $diff_time = now()->parse(gmdate('H:i:s', $to->diffInSeconds($from)));

        $available_time = '00:00:00';
        if ($to->diffInSeconds($from) <= $processing_time * 60) {
            $available_time = gmdate('H:i:s', $processtime->diffInSeconds($diff_time));
        }

        return $available_time;
    }
}

if (! function_exists('orderRunningTime')) {
    function orderRunningTime($seen_time)
    {
        $available_time = null;
        try {
            $to = Carbon::createFromFormat('Y-m-d H:i:s', $seen_time);
            $from = now();
            $available_time = $to->diffInSeconds($from);
        } catch (\Exception $e) {
            //throw $th;
        }

        return $available_time;
    }
}

if (! function_exists('getLocation')) {
    /**
     * get location details
     */
    function getLocation(string $address)
    {
        $key = config('services.google.map_api_key');
        $response = Http::post("https://maps.googleapis.com/maps/api/place/textsearch/json?query=$address&key=$key")->json();

        return count($response['results']) > 0 ? $response['results'][0]['geometry']['location'] : false;
    }
}

if (! function_exists('customerGiftCardAvailableAmount')) {
    /**
     * Customer gift card available amount
     */
    function customerGiftCardAvailableAmount($user_id)
    {
        $deposit = DB::table('gift_cards')->where('user_id', $user_id)->where('status', PaymentStatus::SUCCESS->value)->sum('amount');
        $withdraw = DB::table('gift_card_transactions')->where('user_id', $user_id)->sum('amount');

        return $deposit - $withdraw;
    }
}

if (! function_exists('storeExceptionLog')) {
    /**
     * Customer gift card available amount
     */
    function storeExceptionLog(Exception $e)
    {
        Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

        return response()->json(['message' => 'Something went wrong, please try again'], 300);
    }
}

if (! function_exists('appConfiguration')) {
    /**
     * app configuration
     */
    function appConfiguration()
    {
        // \Illuminate\Support\Facades\Artisan::call('migrate');
        // \Illuminate\Support\Facades\Artisan::call('db:seed --class=SettingSeeder');
        // \Illuminate\Support\Facades\Artisan::call('db:seed --class=PermissionSeeder');
        // \Illuminate\Support\Facades\Artisan::call('storage:link');

        config()->set('app.name', setting('app_title'));
        config()->set('app.timezone', setting('timezone'));

        $mail = json_decode(setting('mail'));

        config()->set('mail.mailers.smtp.transport', $mail->mailer);
        config()->set('mail.mailers.smtp.encryption', $mail->encryption);
        config()->set('mail.mailers.smtp.port', $mail->port);
        config()->set('mail.mailers.smtp.host', $mail->host);
        config()->set('mail.mailers.smtp.username', $mail->username);
        config()->set('mail.mailers.smtp.password', $mail->password);
        config()->set('mail.from.address', $mail->from_address);
        config()->set('mail.from.name', $mail->from_name);

        config()->set('app.timezone', setting('timezone'));

        $payment = json_decode(setting('payment'));
        config()->set('services.stripe.key', $payment->stripe->key);
        config()->set('services.stripe.secret', $payment->stripe->secret);
        config()->set('services.stripe.enable', (bool) $payment->stripe->enable);

        $mode = $payment->paypal?->mode ?? 'sandbox';
        config()->set('paypal.mode', $mode);
        config()->set("paypal.{$mode}.client_id", $payment->paypal->client_id);
        config()->set("paypal.{$mode}.client_secret", $payment->paypal->client_secret);
        config()->set('paypal.enable', (bool) $payment->paypal->enable);

        $oauth = json_decode(setting('oauth'));

        config()->set('services.google.client_id', $oauth->google->client_id);
        config()->set('services.google.client_secret', $oauth->google->client_secret);
        config()->set('services.google.redirect', $oauth->google->redirect);
        config()->set('services.google.enable', (bool) $oauth->google->enable);

        config()->set('services.facebook.client_id', $oauth->facebook->client_id);
        config()->set('services.facebook.client_secret', $oauth->facebook->client_secret);
        config()->set('services.facebook.redirect', $oauth->facebook->redirect);
        config()->set('services.facebook.enable', (bool) $oauth->facebook->enable);

        $sms = json_decode(setting('sms'));

        config()->set('services.twilio.sid', $sms->twilio->sid);
        config()->set('services.twilio.auth_token', $sms->twilio->auth_token);
        config()->set('services.twilio.verify_sid', $sms->twilio->verify_sid);
        config()->set('services.twilio.enable', (bool) $sms->twilio->enable);

        $xero = json_decode(setting('xero'));
        config()->set('services.xero.client_id', $xero->client_id);
        config()->set('services.xero.client_secret', $xero->client_secret);
        config()->set('services.xero.redirect', $xero->redirect_url);
    }
}
