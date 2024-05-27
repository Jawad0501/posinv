<?php

namespace App\Http\Controllers\Api\Backend\POS;

use App\Http\Controllers\Api\Backend\Food\MenuController as FoodMenuController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\FoodRequest;
use App\Http\Resources\Backend\POS\MenuCollection;
use App\Http\Resources\Backend\POS\MenuResource;
use App\Models\Category;
use App\Models\Food;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// NEW
use App\Models\Stock;

/**
 * @group POS Menu management
 *
 * APIs to POS Menu management
 */
class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\POS\MenuCollection
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
    public function index(Request $request)
    {
        // $foods = Food::query()->with('addons', 'variants');

        $foods = Stock::query()->with('ingredient');


        if (request()->has('category') && ! empty(request()->get('category'))) {
            $foodIds = DB::table('ingredients')->where('category_id', request()->get('category'))->pluck('id');
            $foods = $foods->whereIn('id', $foodIds);
        }
        if ($request->has('keyword') && ! empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $foods = $foods->where(function ($query) use ($keyword) {
                $query->whereHas('ingredient', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            });
        }

        $foods = $foods->latest('id')->paginate(100);

        return new MenuCollection($foods);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Menu successfully stored.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *    "message": "The categories field is required. (and 7 more errors)",
     *    "errors": {
     *        "categories": [
     *            "The categories field is required."
     *        ],
     *        "name": [
     *            "The food name field is required."
     *        ],
     *        "price": [
     *            "The food price field is required."
     *        ],
     *        "processing_time": [
     *            "The food processing time field is required."
     *        ],
     *        "tax_vat": [
     *            "The tax vat field is required."
     *        ],
     *        "calorie": [
     *            "The food calorie field is required."
     *        ],
     *        "image": [
     *            "The food image field is required."
     *        ],
     *        "description": [
     *            "The food description field is required."
     *        ]
     *    }
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FoodRequest $request)
    {
        $menu = new FoodMenuController;

        return $menu->store($request);
    }

    /**
     * Get menu details
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\POS\MenuResource
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
    public function details(Food $food)
    {
        $food->load('variants', 'addons');

        return new MenuResource($food);
    }

    /**
     * Get menu details by variant
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\POS\MenuResource
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
    public function variant(Food $food, $id)
    {
        $food->load('variants', 'addons');
        $variant = Variant::where('id', $id)->where('food_id', $food->id)->first(['id', 'name', 'price']);

        return new MenuResource($food, $variant);
    }

    /**
     * Menu category check this category is drink or not
     *
     * @authenticated
     *
     * @response 200 {
     *   "count": 0 or >1
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return int
     */
    public function categoryCheck()
    {
        return Category::whereIn('id', request()->has('categories') ? request()->get('categories') : [])->where('is_drinks', true)->count();
    }

    /**
     * Get ingredient wise menu price
     *
     * @authenticated
     *
     * @response 200 {
     *   "price": 260.1
     * }
     * @response status=404 scenario="Not Found" {
     *     "message": "404 Not Found."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Internal Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ingredientPrice($id)
    {
        $menu = new FoodMenuController;

        return $menu->ingredientPrice($id);
    }
}
