<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Verify Notification Management
 *
 * APIs to user verification notification
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email or phone verification notification.
     *
     * @response 200
     * {
     *    'message': 'Your verification OTP code has been send.'
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
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your account has been verified.']);
        }

        sendVerificationNotification($request->user());
        // $request->user()->sendEmailVerificationNotification();

        return response()->json(['message', 'Your verification OTP code has been send.']);
    }
}
