<?php

namespace App\Http\Controllers\Backend\Production;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductionRequest;
use App\Models\Production;
use App\Models\ProductionUnit;
use App\Models\ProductionUnitItem;
use App\Models\Stock;
use Yajra\DataTables\Facades\DataTables;

class ProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_production');
        if (request()->ajax()) {
            $productions = Production::query()->with('unit:id,food_id,variant_id', 'unit.food:id,name', 'unit.variant:id,name');

            return DataTables::of($productions)
                ->addIndexColumn()
                ->editColumn('production_date', function ($data) {
                    return format_date($data->production_date);
                })
                ->editColumn('expire_date', function ($data) {
                    return format_date($data->expire_date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('production.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_production'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.production.production.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_production');
        $units = ProductionUnit::with('food:id,name', 'variant:id,name')->latest('id')->get();

        return view('pages.production.production.form', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductionRequest $request)
    {
        $input = $request->all();
        $input['production_unit_id'] = $request->production_unit;

        $items = ProductionUnitItem::where('production_unit_id', $request->production_unit)->get();
        foreach ($items as $item) {
            $stock = Stock::with('ingredient:id,unit_id', 'ingredient.unit:id,slug')->where('ingredient_id', $item->ingredient_id)->first();
            if ($stock) {
                $ingredientUnit = $stock->ingredient?->unit?->slug;
                if ($ingredientUnit == $item->unit) {
                    $stockOut = $item->quantity;
                } else {
                    if (($ingredientUnit == 'kg' && $item->unit == 'gm') || ($ingredientUnit == 'ltr' && $item->unit == 'ml')) {
                        $stockOut = 1000 - $item->quantity;
                    } elseif (($ingredientUnit == 'gm' && $item->unitunit == 'kg') || ($ingredientUnit == 'ml' && $item->unit == 'ltr')) {
                        $stockOut = 1000 * $item->quantity;
                    } else {
                        $stockOut = $item->quantity;
                    }
                }

                $stock->qty_amount -= ($stockOut * $request->serving_unit);
                $stock->save();
            }
        }

        Production::create($input);

        return response()->json(['message' => 'Production added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Production $production)
    {
        $this->authorize('show_production');

        return view('pages.production.production.show', compact('production'));
    }
}
