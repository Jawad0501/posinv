<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * @group Register Management
 *
 * APIs to register a user
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @response 200
     * {
     *    'token': '1|dj7pC38puk7945Z3DqlWd5PvjMfVwoj9TMSSSSnX',
     *    'message': 'You are successfully registered.'
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
        if ($request->has('google_id')) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'string', 'max:255', 'unique:users,phone'],
                'password' => ['required', Rules\Password::defaults()],
            ]);
        }

        if ($request->has('google_id')) {
            $user = User::query()->where('google_id', $request->google_id)->first();
        }
        if (! isset($user)) {
            $name = explode(' ', $request->name);
            $user = User::query()->create([
                'first_name' => $name[0],
                'last_name' => isset($name[1]) ? $name[1] : null,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password ?? rand()),
                'google_id' => $request->google_id,
                'email_verified_at' => $request->has('google_id') ? now() : null,
            ]);
        }

        Auth::login($user);

        if (! $request->has('google_id')) {
            sendVerificationNotification($user);
        }

        $response['message'] = 'You are successfully registered.';

        if ($request->is('api/*')) {
            $response['token'] = $user->createToken($user->full_name)->plainTextToken;
        }

        return response()->json($response);
    }
}
