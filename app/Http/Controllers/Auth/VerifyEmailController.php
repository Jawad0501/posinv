<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

/**
 * @group Verification Management
 *
 * APIs to user verification
 */
class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email or phone as verified.
     *
     * @response 200
     * {
     *    'message': 'Your verification successfully completed.'
     * }
     * @response 302
     * {
     *    'message': 'Invalid verification code entered!'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['otp_code' => ['required', 'string']]);

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'You are already verified']);
        }

        $msg = 'Invalid verification code entered!';
        $statusCode = 302;

        if ($request->user()->verify_field == 'email') {
            if ($request->user()->otp_code == $request->otp_code) {
                $msg = 'Your verification successfully completed.';
                $statusCode = 200;
            }
        } else {
            $token = config('services.twilio.auth_token');
            $twilio_sid = config('services.twilio.sid');
            $twilio_verify_sid = config('services.twilio.verify_sid');

            $twilio = new Client($twilio_sid, $token);
            $verify = ['code' => $request->otp_code, 'to' => $request->user()->phone];

            $verification = $twilio->verify->v2->services($twilio_verify_sid)->verificationChecks->create($verify);

            if ($verification->valid) {
                $msg = 'Your verification successfully completed.';
                $statusCode = 200;
            }
        }

        // if ($request->user()->markEmailAsVerified()) {
        //     event(new Verified($request->user()));
        // }

        if ($statusCode == 200) {
            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
            $request->user()->fill(['otp_code' => null, 'verify_field' => null])->save();
        }

        return response()->json(['message' => $msg], $statusCode);
    }
}
