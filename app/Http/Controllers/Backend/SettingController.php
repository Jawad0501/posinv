<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SettingRequest;
use App\Models\Setting;
use stdClass;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $this->authorize('show_setting');

        $data = new stdClass;

        $data->tabs = [
            'general',
            'finance',
            // 'email-Settings',
            // 'payment',
            // 'oAuth',
            // 'SMS-Settings',
            'file',
            // 'hero-Section',
            // 'contact-Section',
        ];

        $data->timezones = json_decode(file_get_contents(resource_path('views/timezone.json')));

        $data->dateFormats = ['d/M/Y', 'M/d/Y', 'Y/M/d', 'd-M-Y', 'M-d-Y', 'Y-M-d', 'd-m-Y'];

        $data->currencySymbols = ['£', '$', '৳', '₹', 'AED'];

        $data->hero_section_content = json_decode(setting('hero_section_content'));
        $data->contact_content = json_decode(setting('contact_content'));

        return view('pages.setting.index', compact('data'));
    }

    /**
     * Update the settings.
     */
    public function update(SettingRequest $request)
    {
        $this->authorize('edit_setting');
        $inputs = $request->validated();

        if ($request->hasFile('dark_logo')) {
            $dark_logo = file_upload($request->dark_logo, 'setting', setting('dark_logo'));
        }
        if ($request->hasFile('light_logo')) {
            $light_logo = file_upload($request->light_logo, 'setting', setting('light_logo'));
        }
        if ($request->hasFile('favicon')) {
            $favicon = file_upload($request->favicon, 'setting', setting('favicon'));
        }
        if ($request->hasFile('invoice_logo')) {
            $invoice_logo = file_upload($request->invoice_logo, 'setting', setting('invoice_logo'));
        }
        if ($request->hasFile('default_image')) {
            $default_image = file_upload($request->default_image, 'setting', setting('default_image'));
        }

        $inputs['dark_logo'] = $dark_logo ?? setting('dark_logo');
        $inputs['light_logo'] = $light_logo ?? setting('light_logo');
        $inputs['favicon'] = $favicon ?? setting('favicon');
        $inputs['invoice_logo'] = $invoice_logo ?? setting('invoice_logo');
        $inputs['default_image'] = $default_image ?? setting('default_image');

        // $hero_content = json_decode(setting('hero_section_content'));
        // $inputs['hero_section_content']['image'] = $request->has('hero_section_content.image') ? file_upload($request->file('hero_section_content.image'), 'setting', $hero_content->image) : $hero_content->image;

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Artisan::call('cache:clear');

        return response()->json(['message' => 'Setting updated successfully']);
    }
}
