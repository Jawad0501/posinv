<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * @group Authentication Management
 *
 * APIs to user authentication
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view(isAdminUrlRequest() ? 'pages.auth.login' : 'auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @response 200
     * {
     *    'token': '1|dj7pC38puk7945Z3DqlWd5PvjMfVwoj9TMSSSSnX',
     *    'message': 'You are logged in successfully'
     * }
     * @response 401
     * {
     *    'message': 'Your credentials does not match our records'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (! $request->is('api/*')) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'You are logged in successfully',
            ]);
        }

        $guard = $request->is('api/admin*') ? 'staff' : null;
        $user = auth($guard)->user();

        return response()->json([
            'token' => $user->createToken($user->name ?? $user->full_name)->plainTextToken,
            'message' => 'You are logged in successfully',
        ]);

    }

    /**
     * Destroy an authenticated session.
     *
     * @authenticated
     *
     * @response 200
     * {
     *    "message": 'You are logged out successfully'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Request $request)
    {
        $guard = isAdminUrlRequest() || $request->is('api/admin*') ? 'staff' : 'web';

        Auth::guard($guard)->logout();

        if ($request->is('api/*')) {
            $request->user()->currentAccessToken()->delete();
        } else {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($request->is('api/*') || $request->ajax()) {
            return response()->json(['message' => 'You are logged out successfully']);
        }

        return redirect()->to('/');
    }
}
