<?php

namespace App\Http\Controllers\Api\Backend\Food;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\FoodRequest;
use App\Http\Resources\Backend\Food\MenuCollection;
use App\Http\Resources\Backend\Food\MenuResource;
use App\Models\Category;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * @group Food Menu Management
 *
 * APIs to Menu
 */
class MenuController extends Controller
{
    public $food;

    public function __construct()
    {
        $this->food = Food::query();
    }

    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Food\MenuCollection
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
     * @queryParam keyword
     */
    public function index(Request $request)
    {
        $foods = Food::query()->with('addons', 'variants');

        if ($request->has('keyword')) {
            $foods = $foods->where('name', 'like', "%$request->keyword%")
                ->orWhere('price', 'like', "%$request->keyword%")
                ->orWhere('description', 'like', "%$request->keyword%")
                ->orWhere('calorie', 'like', "%$request->keyword%")
                ->orWhere('processing_time', 'like', "%$request->keyword%")
                ->orWhere('tax_vat', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient', 'name', 'like', "%$request->keyword%");
        }
        $foods = $foods->latest('id')->paginate();

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
     */
    public function store(FoodRequest $request)
    {
        $request->saved();

        return response()->json(['message' => 'New menu added successfully']);
    }

    /**
     * Display the specified resource.
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
    public function show(Food $menu)
    {
        $menu->load('variants', 'mealPeriods', 'addons', 'allergies');

        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Menu successfully updated.'
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
     */
    public function update(FoodRequest $request, Food $menu)
    {
        $request->saved($menu);

        return response()->json(['message' => 'Menu updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $menu)
    {
        delete_uploaded_file($menu->image);
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully']);
    }

    /**
     * category check
     *
     * @param $category
     * @return int
     */
    public function categoryCheck()
    {
        return Category::whereIn('id', request()->has('categories') ? request()->get('categories') : [])->where('is_drinks', true)->count();
    }

    /**
     * ingredient wise price
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ingredientPrice($id)
    {
        $purchaseItem = PurchaseItem::where('ingredient_id', $id)->latest('id')->first();

        if ($purchaseItem) {
            $purchase_price = $purchaseItem->unit_price;
        } else {
            $purchase_price = Ingredient::where('id', $id)->value('purchase_price');
        }

        $purchase_price += ($purchase_price / 100) * 2;

        return response()->json(['price' => $purchase_price]);
    }

    /**
     * ingredient wise price
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function partials()
    {
        $data = new stdClass;
        $data->categories = collect(DB::table('categories')->where('status', true)->select('id', 'name')->get()->toArray())->flatten()->all();
        $data->meal_periods = collect(DB::table('meal_periods')->where('status', true)->select('id', 'name')->get()->toArray())->flatten()->all();
        $data->addons = collect(DB::table('addons')->where('status', true)->select('id', 'name')->get()->toArray())->flatten()->all();
        $data->allergies = collect(DB::table('allergies')->where('status', true)->select('id', 'name')->get()->toArray())->flatten()->all();
        $data->ingredients = collect(DB::table('ingredients')->join('ingredient_units', 'ingredient_units.id', '=', 'ingredients.unit_id')->select('ingredients.id', 'ingredients.name', 'ingredients.purchase_price', 'ingredient_units.name as unit_name')->get()->toArray())->flatten()->all();
        $data->suppliers = collect(DB::table('suppliers')->select('id', 'name')->get()->toArray())->flatten()->all();
        $data->banks = collect(DB::table('banks')->select('id', 'name')->get()->toArray())->flatten()->all();

        return response()->json($data);
    }
}
