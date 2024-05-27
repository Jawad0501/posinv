<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use XeroAPI\XeroPHP\Api\PayrollUkApi;
use XeroAPI\XeroPHP\Configuration;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $apiInstance;

    public $tenant_id;

    /**
     * define api instance
     *
     * @return bool
     */
    public function defineApiInstance()
    {
        $xero = DB::table('xeros')->where('staff_id', auth()->id())->first();
        $config = Configuration::getDefaultConfiguration()->setAccessToken($xero->token);
        $this->apiInstance = new PayrollUkApi(new Client(), $config);
        $this->tenant_id = $xero->tenant_id;

        return true;
    }

    /**
     * render table button group
     */
    public function buttonGroup($data)
    {
        return view('pages.btn-group', compact('data'));
    }
}
