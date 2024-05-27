<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\MenuController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\FoodRequest;
use App\Http\Responses\CreateFoodResponse;
use App\Imports\FoodsImport;
use App\Models\Category;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_food');
        if (request()->ajax()) {
            return DataTables::of(Food::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.menu.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_food'],
                        ['url' => route('food.menu.edit', $data->id), 'type' => 'edit', 'can' => 'edit_food'],
                        ['url' => route('food.menu.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_food'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.item.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_food');

        return new CreateFoodResponse;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodRequest $request)
    {
        $this->authorize('create_food');
        $request->saved();

        return response()->json(['message' => 'New menu added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $menu)
    {
        $this->authorize('show_food');
        $food = $menu->load('categories', 'addons', 'allergies', 'variants', 'mealPeriods');

        return view('pages.food.item.show', compact('food'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $menu)
    {
        $this->authorize('edit_food');
        $food = $menu->load('mealPeriods', 'categories', 'addons', 'allergies', 'variants:food_id,name');

        return new CreateFoodResponse($food);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FoodRequest $request, Food $menu)
    {
        $this->authorize('edit_food');
        $request->saved($menu);

        return response()->json(['message' => 'Menu updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $menu)
    {
        $this->authorize('delete_food');
        $con = new MenuController;

        return $con->destroy($menu);
    }

    /**
     * showUploadForm
     */
    public function showUploadForm()
    {
        return view('pages.food.item.upload-form');
    }

    /**
     * upload
     */
    public function upload(Request $request)
    {
        $this->validate($request, ['file' => 'required|file|mimes:xlsx,csv|max:2048']);
        Excel::import(new FoodsImport, $request->file);

        return response()->json(['message' => 'Supplier uploaded successfully']);
    }

    /**
     * category check
     */
    public function categoryCheck()
    {
        return Category::whereIn('id', request()->has('categories') ? request()->get('categories') : [])->where('is_drinks', true)->count();
    }

    /**
     * ingredient wise price
     */
    public function ingredientPrice($id)
    {
        $purchaseItem = PurchaseItem::where('ingredient_id', $id)->latest('id')->first();

        if ($purchaseItem) {
            $purchase_price = $purchaseItem->unit_price;
        } else {
            $purchase_price = Ingredient::where('id', $id)->firstOrFail(['id', 'purchase_price'])->purchase_price;
        }

        $purchase_price += ($purchase_price / 100) * 2;

        return response()->json(['price' => $purchase_price]);
    }
}
