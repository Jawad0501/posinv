<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\UserResource;
use Illuminate\Http\Request;

/**
 * @group User Management
 *
 * APIs to manage authenticated user information
 */
class UserController extends Controller
{
    /**
     * Shows authenticated administration information
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Frontend\UserResource
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Shows authenticated administration information
     *
     * @authenticated
     *
     * @response status=200 {
     *     "message": 'Your verification OTP code has been send.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function phoneVerify(Request $request)
    {
        $request->validate(['phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11']);

        sendVerificationNotification($request->user(), ['phone' => $request->phone]);

        return response()->json(['message', 'Your verification OTP code has been send.']);
    }
}
