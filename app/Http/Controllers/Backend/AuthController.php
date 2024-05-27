<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * showLoginPage
     */
    public function showLoginPage()
    {
        return view('auth.admin-login');
    }

    /**
     * staff guard login
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $fieldType => $request->email,
            'password' => $request->password,
        ];

        $remember = (! empty($request->remember)) ? true : false;

        if (Auth::guard('staff')->attempt($credentials, $remember)) {

            $staff = Staff::find(auth('staff')->id());

            if (! $staff->status) {
                Auth::logout();

                return response()->json(['message' => 'Your account has been blocked. Please contact admin'], 302);
            }
            $staff->ip_address = $request->ip();
            $staff->last_login = now();
            $staff->save();

            return response()->json(['message' => 'You are login successfully']);
        } else {
            return response()->json(['message' => 'Your credentials do not match our records'], 401);
        }

        return response()->json($request, 300);
    }

    /**
     * staff guard logout
     */
    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        return response()->json(['message' => 'Logout successfully']);
    }

    public function edit(Request $request)
    {
        Auth::guard('staff')->logout();

        return response()->json(['message' => 'Logout successfully']);
    }
}
