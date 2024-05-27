<?php

namespace App\Http\Controllers\Api\Backend\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\ProductionUnitRequest;
use App\Http\Resources\Backend\Production\ProductionUnitCollection;
use App\Http\Resources\Backend\Production\ProductionUnitResource;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\ProductionUnit;
use App\Models\ProductionUnitItem;
use Illuminate\Http\Request;
use stdClass;

/**
 * @group Production Unit Management
 *
 * APIs to Production Unit manage
 */
class ProductionUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Production\ProductionUnitCollection
     *
     * @apiResourceModel App\Models\ProductionUnit
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function index(Request $request)
    {
        $units = ProductionUnit::query()->with('food:id,name', 'variant:id,name')->whereLike(['food,name', 'variant,name'], $request->keyword)->withSum('items', 'price')->paginate();

        return new ProductionUnitCollection($units);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = new stdClass;
        if (request()->has('is_food')) {
            $data->foods = Food::latest()->get(['id', 'name']);
        }
        $data->ingredients = Ingredient::with(['unit:id,name,slug', 'purchaseItems' => fn ($q) => $q->latest('id')->select(['ingredient_id', 'unit_price'])])->withCount('purchaseItems')->latest('id')->get(['id', 'unit_id', 'name', 'code']);

        return $data;
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
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductionUnitRequest $request)
    {
        $unit = ProductionUnit::create([
            'food_id' => $request->food,
            'variant_id' => $request->variant,
        ]);

        foreach ($request->items as $item) {
            $unit->items()->create([
                'ingredient_id' => $item['ingredient'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['message' => 'Production Cost Per Unit added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Production\ProductionUnitResource
     *
     * @apiResourceModel App\Models\ProductionUnit
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \App\Http\Resources\Backend\Production\ProductionUnitResource
     */
    public function show(ProductionUnit $productionUnit)
    {
        $productionUnit->load('items', 'items.ingredient:id,name');

        return new ProductionUnitResource($productionUnit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductionUnitRequest $request, ProductionUnit $productionUnit)
    {
        $productionUnit->update([
            'food_id' => $request->food,
            'variant_id' => $request->variant,
        ]);

        foreach ($request->items as $item) {
            $productionUnitItem = $productionUnit->items()->find(isset($item['id']) ? $item['id'] : 0);

            $data = [
                'ingredient_id' => $item['ingredient'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'price' => $item['price'],
            ];

            $productionUnitItem ? $productionUnitItem->update($data) : $productionUnit->items()->create($data);
        }

        return response()->json(['message' => 'Production Cost Per Unit updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionUnit $productionUnit)
    {
        $productionUnit->delete();

        return response()->json(['message' => 'Production Cost Per Unit deleted successfully']);
    }

    /**
     * Remove the specified resource from items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemDestroy($id)
    {
        ProductionUnitItem::find($id)->delete();

        return response()->json(true);
    }

    /**
     * Get food variant
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function foodVariant(Food $food)
    {
        $variants = $food->variants()->get(['id', 'name']);

        return response()->json($variants);
    }
}
