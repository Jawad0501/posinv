<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Api\Backend\POS\MenuController as ApiMenuController;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Allergy;
use App\Models\Category;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\MealPeriod;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public $menu;

    /**
     * Assigned api menu controller
     */
    public function __construct()
    {
        $this->menu = new ApiMenuController;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $foods = $this->menu->index($request);

        return view('pages.pos.food.index', compact('foods'));
    }

    /**
     * Handle the incoming request.
     */
    public function create()
    {
        $data['mealPeriods'] = MealPeriod::latest('id')->get(['id', 'name']);
        $data['categories'] = Category::latest('id')->get(['id', 'name']);
        $data['addons'] = Addon::latest('id')->get(['id', 'name']);
        $data['allergies'] = Allergy::latest('id')->get(['id', 'name']);
        $data['ingredients'] = Ingredient::latest('id')->get(['id', 'name']);

        return view('pages.pos.food.create', compact('data'));
    }

    /**
     * Display the specified resource.
     */
    public function details(Request $request)
    {
        $ingredient_id = $request->food;

        $ingredient = Ingredient::query()->where('id', $ingredient_id)->first();

        $ingredient_purchase_items = $ingredient->purchaseItems()->get();


        // $food = $this->menu->details($food);
        // $cart = session()->get('pos_cart');
        // $menu = isset($cart[$food->id]) ? $cart[$food->id] : null;

        return view('pages.pos.food.details', compact('ingredient', 'ingredient_purchase_items'));
    }

    /**
     * Display the specified resource.
     */
    public function variant(Food $food, $id)
    {
        return $this->menu->variant($food, $id);
    }
}
