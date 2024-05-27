<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\MenuCollection;
use App\Models\Food;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @apiResourceCollection App\Http\Resources\Frontend\MenuCollection
     *
     * @apiResourceModel App\Models\Food
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @bodyParam category int The id of the category. Example: 1
     * @bodyParam keyword string. Example: 'example'
     */
    public function __invoke(Request $request)
    {
        $menus = Food::query()->with('allergies', 'addons', 'variants')->whereIn('slug', $request->slugs)->get();

        return new MenuCollection($menus);
    }
}
