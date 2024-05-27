<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group User Profile Management
 *
 * APIs to manage authenticated user profile
 */
class ProfileController extends Controller
{
    /**
     * Shows authenticated administration information
     *
     * @authenticated
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Update the authenticated user profile information
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Profile successfully updated.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
            'phone' => ['required', 'string', 'max:255', 'unique:users,phone,'.auth()->id()],
            'image' => ['nullable', 'image', 'mimes:jpg,png,bmp,jpeg,webp', 'max:2048'],
        ]);

        $validated['image'] = $request->hasFile('image') ? file_upload($request->file('image'), 'customer', $request->user()->image) : $request->user()->image;

        $request->user()->fill($validated)->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }

    /**
     * Update the authenticated user password
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Password updated successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function passwordChange(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string|max:255',
            'password' => ['required', Password::defaults()],
        ]);

        if (Hash::check($request->old_password, $request->user()->password)) {
            if (! Hash::check($request->password, $request->user()->password)) {

                $request->user()->fill(['password' => bcrypt($request->password)])->save();

                Auth::guard('web')->logout();

                return response()->json(['message' => 'Password updated successfully']);
            } else {
                return response()->json(['message' => 'New password can not be same as current password'], 302);
            }
        } else {
            return response()->json(['message' => 'Password does not match'], 302);
        }
    }
}
