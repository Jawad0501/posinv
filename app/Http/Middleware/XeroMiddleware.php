<?php

namespace App\Http\Middleware;

use App\Models\Xero;
use Closure;
use Illuminate\Http\Request;

class XeroMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $xero = Xero::where('staff_id', auth()->id())->first();
        $provider = xeroProvider();

        if ($xero) {
            if (time() > $xero->expires) {
                $newAccessToken = $provider->getAccessToken('refresh_token', [
                    'refresh_token' => $xero->refresh_token,
                ]);

                // Save my token, expiration and refresh token
                setXeroToken(
                    $newAccessToken->getToken(),
                    $newAccessToken->getExpires(),
                    $xero->tenant_id,
                    $newAccessToken->getRefreshToken(),
                    $newAccessToken->getValues()['id_token']
                );
            }

            return $next($request);
        } else {
            $options = [
                'scope' => [
                    'openid email profile offline_access assets projects accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments payroll.employees payroll.employees.read payroll.settings payroll.settings.read',
                ],
            ];

            // This returns the authorizeUrl with necessary parameters applied (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl($options);

            // Save the state generated for you and store it to the session.
            // For security, on callback we compare the saved state with the one returned to ensure they match.
            session()->put('xero_oauth2state', $provider->getState());

            // Redirect the user to the authorization URL.
            return redirect()->to($authorizationUrl);
        }
    }
}
