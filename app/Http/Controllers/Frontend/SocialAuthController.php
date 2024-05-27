<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function index($driver)
    {
        if (request()->is('menu*') || request()->is('cart*')) {
            session()->put('driver', $driver);
        }
        if (! config('services.'.$driver.'.login')) {
            return back();
        }

        return Socialite::driver($driver)->redirect();
    }

    /**
     * Create a new controller instance.
     */
    public function handleCallback($driver)
    {
        $id = $driver == 'facebook' ? 'facebook_id' : 'google_id';
        $user = Socialite::driver($driver)->user();
        $finduser = User::where($id, $user->id)->first();

        if (! $finduser) {
            $first_name = isset($user->user['given_name']) ? $user->user['given_name'] : $user->name;
            $last_name = isset($user->user['family_name']) ? $user->user['family_name'] : null;

            $finduser = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                $id => $user->id,
                'password' => encrypt(rand()),
            ]);
        }
        Auth::login($finduser);

        return redirect()->route('phone.verify', ['redirect' => encrypt(rand())]);
    }
}
