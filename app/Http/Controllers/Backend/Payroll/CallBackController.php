<?php

namespace App\Http\Controllers\Backend\Payroll;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use XeroAPI\XeroPHP\Api\IdentityApi;
use XeroAPI\XeroPHP\Configuration;

class CallBackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $provider = xeroProvider();

        // If we don't have an authorization code then get one
        if (! isset($request->code)) {
            return 'Something went wrong, no authorization code found';
        } elseif (empty($request->state) || ($request->state !== session()->get('xero_oauth2state'))) {
            session()->forget('xero_oauth2state');

            return 'Invalid State';
        } else {
            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);

                $config = Configuration::getDefaultConfiguration()->setAccessToken((string) $accessToken->getToken());
                $identityInstance = new IdentityApi(new Client(['verify' => false]), $config);

                $result = $identityInstance->getConnections();

                // Save my tokens, expiration tenant_id
                setXeroToken(
                    $accessToken->getToken(),
                    $accessToken->getExpires(),
                    $result[0]->getTenantId(),
                    $accessToken->getRefreshToken(),
                    $accessToken->getValues()['id_token']
                );

                return redirect()->route('payroll.employee.index');
            } catch (IdentityProviderException $e) {
                return 'Callback failed: '.$e->getMessage();
            }
        }
    }
}
