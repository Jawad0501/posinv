<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @group Forgot Password
 *
 * APIs to forgot password manage
 */
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @response 200
     * {
     *    "message": "We have emailed your password reset link!"
     * }
     * @response 422
     * {
     *       "message": "We can't find a user with that email address.",
     *       "errors": {
     *           "email": [
     *               "We can't find a user with that email address."
     *           ]
     *       }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $guard = isAdminUrlRequest() || $request->is('api/admin*') ? 'admins' : null;
        $status = Password::broker($guard)->sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        }
        throw ValidationException::withMessages(['email' => [trans($status)]]);
    }
}
