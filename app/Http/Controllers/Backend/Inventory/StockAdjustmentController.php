<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Api\Backend\Inventory\StockAdjustmentController as ApiStockAdjustmentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StockAdjustmentRequest;
use App\Models\Ingredient;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use Yajra\DataTables\Facades\DataTables;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_stock_adjustment');
        if (request()->ajax()) {

            $adjustments = StockAdjustment::with('staff:id,name', 'items:id,stock_adjustment_id')->latest('id')->select('*');

            return DataTables::eloquent($adjustments)
                ->addIndexColumn()
                ->addColumn('items_count', function ($data) {
                    return $data->items->count();
                })
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('stock-adjustment.show', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_stock_adjustment'],
                        ['url' => route('stock-adjustment.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_stock_adjustment'],
                        ['url' => route('stock-adjustment.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_stock_adjustment'],
                    ]);
                })
                ->rawColumns(['action', 'items_count'])
                ->toJson();
        }

        return view('pages.inventory.stock-adjustment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_stock_adjustment');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name', 'code']);

        return view('pages.inventory.stock-adjustment.form', compact('persons', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockAdjustmentRequest $request)
    {
        $this->authorize('create_stock_adjustment');

        $adjustment = StockAdjustment::create([
            'staff_id' => $request->person,
            'date' => $request->date,
            'note' => $request->note,
            'added_by' => auth()->user()->name,
        ]);

        $adjustment->reference_no = sprintf('%s%05s', '', $adjustment->id);
        $adjustment->save();

        foreach ($request->ingredient_id as $key => $ingredient_id) {
            $ingredient = Ingredient::find($ingredient_id, ['id']);
            if ($ingredient) {

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'ingredient_id' => $ingredient->id,
                    'quantity_amount' => $request->quantity_amount[$key],
                    'consumption_status' => $request->consumption_status[$key],
                ]);

                $stock = Stock::where('ingredient_id', $ingredient->id)->first();
                if ($stock) {
                    if ($request->consumption_status[$key] == 'plus') {
                        $stock->qty_amount += $request->quantity_amount[$key];
                    } else {
                        $stock->qty_amount -= $request->quantity_amount[$key];
                    }
                    $stock->save();
                }
            }
        }

        return response()->json(['message' => 'Stock adjustment was successfully added']);
    }

    /**
     * Display the specified resource.
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        $this->authorize('show_purchase');
        $stockAdjustment->load('staff:id,name', 'items', 'items.ingredient:id,unit_id,name,code', 'items.ingredient.unit:id,name');

        return view('pages.inventory.stock-adjustment.show', compact('stockAdjustment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockAdjustment $stockAdjustment)
    {
        $this->authorize('edit_stock_adjustment');
        $stockAdjustment->load('staff:id,name', 'items', 'items.ingredient:id,unit_id,name,code', 'items.ingredient.unit:id,name');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $ingredients = Ingredient::latest('id')->get(['id', 'name', 'code']);

        return view('pages.inventory.stock-adjustment.form', compact('persons', 'ingredients', 'stockAdjustment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $this->authorize('edit_stock_adjustment');

        $stockAdjustment->update([
            'staff_id' => $request->person,
            'date' => $request->date,
            'note' => $request->note,
            'added_by' => auth()->user()->name,
        ]);

        foreach ($request->ingredient_id as $key => $ingredient_id) {

            $adjustmentItem = StockAdjustmentItem::where('stock_adjustment_id', $stockAdjustment->id)->where('ingredient_id', $ingredient_id)->first();

            if ($adjustmentItem) {
                // Increament/Decrement stock quantity
                $stock = Stock::where('ingredient_id', $adjustmentItem->ingredient_id)->first();
                if ($stock) {
                    if ($adjustmentItem->consumption_status == 'plus') {
                        $stock->qty_amount -= $adjustmentItem->quantity_amount;
                    } else {
                        $stock->qty_amount += $adjustmentItem->quantity_amount;
                    }

                    $stock->save();

                    // Update Purchase Item
                    $adjustmentItem->update([
                        'quantity_amount' => $request->quantity_amount[$key],
                        'consumption_status' => $request->consumption_status[$key],
                    ]);

                    // Plus/Minus stock quantity
                    if ($request->consumption_status[$key] == 'plus') {
                        $stock->qty_amount += $request->quantity_amount[$key];
                    } else {
                        $stock->qty_amount -= $request->quantity_amount[$key];
                    }
                    $stock->save();
                }
            } else {
                $ingredient = Ingredient::find($ingredient_id, ['id']);
                if ($ingredient) {

                    StockAdjustmentItem::create([
                        'stock_adjustment_id' => $stockAdjustment->id,
                        'ingredient_id' => $ingredient->id,
                        'quantity_amount' => $request->quantity_amount[$key],
                        'consumption_status' => $request->consumption_status[$key],
                    ]);

                    $stock = Stock::where('ingredient_id', $ingredient->id)->first();
                    if ($stock) {
                        if ($request->consumption_status[$key] == 'plus') {
                            $stock->qty_amount += $request->quantity_amount[$key];
                        } else {
                            $stock->qty_amount -= $request->quantity_amount[$key];
                        }
                        $stock->save();
                    }
                }
            }
        }

        return response()->json(['message' => 'Stock adjustment was successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        $this->authorize('delete_stock_adjustment');

        $api = new ApiStockAdjustmentController;

        return $api->destroy($stockAdjustment);
    }

    /**
     * itemDestroy
     */
    public function itemDestroy(StockAdjustmentItem $stockAdjustmentItem)
    {
        $api = new ApiStockAdjustmentController;

        return $api->itemDestroy($stockAdjustmentItem);
    }
}
