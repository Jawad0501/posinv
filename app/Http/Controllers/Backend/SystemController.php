<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    /**
     * cacheFormShow
     */
    public function cacheFormShow()
    {
        return view('pages.system.cache');
    }

    /**
     * cacheFormShow
     */
    public function cacheClear()
    {
        Artisan::call('cache:clear');

        return response()->json(['message' => 'Cache cleared.']);
    }
}
