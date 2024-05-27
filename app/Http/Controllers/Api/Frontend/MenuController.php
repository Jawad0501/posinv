<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\CategoryMenuCollection;
use App\Http\Resources\Frontend\MenuCollection;
use App\Http\Resources\Frontend\MenuResource;
use App\Models\Category;
use App\Models\Food;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @group Authenticated menu management
 *
 * APIs to Authenticated menu management
 */
class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @apiResourceCollection App\Http\Resources\Frontend\CategoryMenuCollection
     *
     * @apiResourceModel App\Models\Category
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
    public function index(Request $request)
    {
        $categories = Category::query()
            ->with(['foods' => fn ($q) => $q->visibility()->sellable()], 'foods.allergies', 'foods.variants')
            ->has('foods')
            ->filter('name', 'category')
            ->whereLike(['foods,name'], $request->keyword)
            ->latest('id')
            ->paginate();

        return new CategoryMenuCollection($categories);
    }

    /**
     * Display popular menu of the resource.
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
     */
    public function popularItem()
    {
        $foodIds = OrderDetails::query()->with('food')->select('food_id', DB::raw('SUM(quantity) as popular'))->groupBy('food_id')->orderBy('popular', 'desc')->pluck('food_id');
        $popularItems = Food::query()->with('allergies:id,image', 'addons', 'addons.subAddons')->whereIn('id', $foodIds)->active()->visibility()->sellable()->take(6)->latest()->get();

        return new MenuCollection($popularItems);
    }

    /**
     * Display the specified resource.
     *
     * @apiResource App\Http\Resources\Frontend\MenuResource
     *
     * @apiResourceModel App\Models\Food
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(string $slug)
    {
        $menu = Food::query()->with('allergies', 'addons', 'addons.subAddons', 'variants')->where('slug', $slug)->active()->visibility()->sellable()->firstOrFail();

        return new MenuResource($menu);
    }
}
