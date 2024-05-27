<?php

namespace App\Http\Controllers\Backend\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\FrontendSettingRequest;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hero_section_content = json_decode(setting('hero_section_content'));
        $contact_content = json_decode(setting('contact_content'));

        return view('pages.frontend.setting', compact('hero_section_content', 'contact_content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FrontendSettingRequest $request)
    {
        $hero_section_content = json_decode(setting('hero_section_content'));

        $hero_section_input = $request->input('hero_section_content');
        $hero_section_input['image'] = $request->has('hero_section_content.image') ? file_upload($request->file('hero_section_content.image'), 'setting', $hero_section_content->image) : $hero_section_content->image;

        Setting::query()->updateOrCreate(['key' => 'hero_section_content'], ['value' => $hero_section_input]);
        Setting::query()->updateOrCreate(['key' => 'contact_content'], ['value' => $request->contact_content]);

        return response()->json(['message' => 'Setting updated successfully']);
    }
}
