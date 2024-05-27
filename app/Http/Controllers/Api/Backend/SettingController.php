<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * @group Application setting
 *
 * APIs to Application setting management
 */
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\SettingCollection
     *
     * @apiResourceModel App\Models\Setting
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index()
    {
        $settings = Setting::get(['key', 'value']);
        $data = [];
        foreach ($settings as $setting) {
            $data[$setting->key] = str_contains($setting->value, 'setting/') ? uploaded_file($setting->value) : $setting->value;
        }

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Setting updated successfully'
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
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
        $request->validate([
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:25',
            'timezone' => 'required|string|max:255',
            'date_format' => 'required|string|max:20',
            'address' => 'required|string',
            'copyright' => 'required|string',
            'restaurant_name' => 'required|string|max:255',
            'restaurant_description' => 'required|string',
        ]);

        $input = $request->all();

        foreach ($input as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // $path = base_path('.env');

        // if (file_exists($path)) {

        //     $envs = [
        //         'APP_NAME' => $request->restaurant_name,
        //         'APP_TIMEZONE' => $request->timezone,
        //     ];

        //     foreach ($envs as $key => $value) {
        //         $envKey = $key.'="'.env($key).'"';
        //         $envVal = $key.'="'.$value.'"';
        //         $replaced = str_replace($envKey, $envVal, file_get_contents($path));

        //         file_put_contents($path, $replaced);
        //     }
        // }
        return response()->json(['message' => 'Setting updated successfully']);
    }

    /**
     * Display a listing of the permission resource.
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
    public function permission()
    {
        $permissions = Permission::query()->pluck('slug');
        $data = [];
        foreach ($permissions as $permission) {
            $data[$permission] = $permission;
        }

        return $data;
    }

    /**
     * Display table layout diagram
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
    public function tableLayoutGet()
    {
        return response()->json(json_decode(setting('table_layout')));
    }

    /**
     * Update table layout diagram
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
    public function tableLayoutUpdate(Request $request)
    {
        $request->validate([
            'tables'              => ['required','array'],
            'tables.*.index'      => ['required','integer'],
            'tables.*.table_type' => ['required','integer'],
            'tables.*.table_no'   => ['required','string'],
        ]);

        Setting::updateOrCreate(['key' => 'table_layout'], ['value' => json_encode($request->tables)]);

        return response()->json(['message' => 'Setting diagram updated successfully']);
    }
}
