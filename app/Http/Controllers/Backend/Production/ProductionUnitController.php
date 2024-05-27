<?php

namespace App\Http\Controllers\Backend\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductionUnitRequest;
use App\Models\Food;
use App\Models\Ingredient;
use App\Models\ProductionUnit;
use App\Models\ProductionUnitItem;
use Yajra\DataTables\Facades\DataTables;

class ProductionUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_production_unit');
        if (request()->ajax()) {

            $units = ProductionUnit::query()->with('food:id,name', 'variant:id,name')->withSum('items', 'price');

            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('production-unit.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_production_unit'],
                        ['url' => route('production-unit.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_production_unit'],
                        ['url' => route('production-unit.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_production_unit'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.production.unit.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_production_unit');

        $data['foods'] = Food::latest()->get(['id', 'name']);
        $data['ingredients'] = Ingredient::with([
            'unit:id,name,slug',
            'purchaseItems' => fn ($q) => $q->latest('id')->select(['ingredient_id', 'unit_price']),
        ])
            ->withCount('purchaseItems')
            ->latest('id')
            ->get(['id', 'unit_id', 'name', 'code']);

        return view('pages.production.unit.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductionUnitRequest $request)
    {
        $this->authorize('create_production_unit');
        $unit = ProductionUnit::create([
            'food_id' => $request->food_name,
            'variant_id' => $request->variant_name,
        ]);

        foreach ($request->products as $key => $product) {
            $ingredient = Ingredient::find(explode('_', $product)[0], ['id']);
            if ($ingredient) {
                $unit->items()->create([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $request->qunatity[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->price[$key],
                ]);
            }
        }

        return response()->json(['message' => 'Production Cost Per Unit added successfully']);
        // explode('_', '18_ml_183.00')
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionUnit $productionUnit)
    {
        $this->authorize('show_production_unit');

        return view('pages.production.unit.show', compact('productionUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionUnit $productionUnit)
    {
        $this->authorize('edit_production_unit');
        $productionUnit->load('items');
        $data['foods'] = Food::with('variants:id,food_id,name')->latest()->get(['id', 'name']);
        $data['ingredients'] = Ingredient::with([
            'unit:id,name,slug',
            'purchaseItems' => fn ($q) => $q->latest('id')->select(['ingredient_id', 'unit_price']),
        ])
            ->withCount('purchaseItems')
            ->latest('id')
            ->get(['id', 'unit_id', 'name', 'code']);

        return view('pages.production.unit.form', compact('productionUnit', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductionUnitRequest $request, ProductionUnit $productionUnit)
    {
        $this->authorize('edit_production_unit');
        $productionUnit->update([
            'id' => $request->name,
            'variant_id' => $request->variant_name,
        ]);

        foreach ($request->items as $key => $item) {
            $product = $request->products[$key];
            $ingredient = Ingredient::find(explode('_', $product)[0], ['id']);

            $productionUnitItem = $productionUnit->items()->find($item);

            if ($productionUnitItem) {
                $productionUnitItem->update([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $request->qunatity[$key],
                    'unit' => $request->units[$key],
                    'price' => $request->price[$key],
                ]);
            } else {
                if ($ingredient) {
                    $productionUnit->items()->updateOrCreate([
                        'ingredient_id' => $ingredient->id,
                        'quantity' => $request->qunatity[$key],
                        'unit' => $request->units[$key],
                        'price' => $request->price[$key],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Production Cost Per Unit updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionUnit $productionUnit)
    {
        $this->authorize('delete_production_unit');
        $productionUnit->delete();

        return response()->json(['message' => 'Production Cost Per Unit deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function itemDestroy($id)
    {
        ProductionUnitItem::find($id)->delete();

        return response()->json(true);
    }

    /**
     * Get food variant
     */
    public function foodVariant(Food $food)
    {
        if (! request()->ajax()) {
            abort(404);
        }
        $variants = $food->variants()->get(['id', 'name']);

        return response()->json($variants);
    }

    /**
     * ingredient
     */
    public function ingredient()
    {
        if (! request()->ajax()) {
            abort(404);
        }
        $ingredients = Ingredient::with([
            'unit:id,name,slug',
            'purchaseItems' => fn ($q) => $q->latest('id')->select(['ingredient_id', 'unit_price']),
        ])
            ->withCount('purchaseItems')
            ->latest('id')
            ->get(['id', 'unit_id', 'name', 'code']);

        return response()->json(['ingredients' => $ingredients]);
    }
}
