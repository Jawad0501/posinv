<?php

namespace App\Http\Controllers\Api\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\StockAdjustmentRequest;
use App\Http\Resources\Backend\Inventory\StockAdjustmentCollection;
use App\Http\Resources\Backend\Inventory\StockAdjustmentResource;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use Illuminate\Http\Request;

/**
 * @group Stock Adjustment Management
 *
 * APIs to Stock Adjustment
 */
class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Inventory\StockAdjustmentCollection
     *
     * @apiResourceModel App\Models\StockAdjustment
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
        $adjusments = StockAdjustment::query()->with('staff:id,name');

        if ($request->has('keyword')) {
            $adjusments = $adjusments->where('reference_no', 'like', "%$request->keyword%")
                ->orWhere('date', 'name', 'like', "%$request->keyword%")
                ->orWhere('note', 'code', 'like', "%$request->keyword%")
                ->orWhere('added_by', 'alert_qty', 'like', "%$request->keyword%")
                ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%");
        }
        $adjusments = $adjusments->latest('id')->paginate();

        return new StockAdjustmentCollection($adjusments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "New category added successfully."
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
    public function store(StockAdjustmentRequest $request)
    {
        $adjustment = StockAdjustment::create([
            'staff_id' => $request->person,
            'date' => $request->date,
            'note' => $request->note,
            'added_by' => auth()->user()->name,
        ]);

        $adjustment->reference_no = sprintf('%s%05s', '', $adjustment->id);
        $adjustment->save();

        foreach ($request->ingredients as $ingredient) {
            StockAdjustmentItem::create([
                'stock_adjustment_id' => $adjustment->id,
                'ingredient_id' => $ingredient['id'],
                'quantity_amount' => $ingredient['quantity_amount'],
                'consumption_status' => $ingredient['consumption_status'],
            ]);

            $stock = Stock::where('ingredient_id', $ingredient['id'])->first();
            if ($stock) {
                if ($ingredient['consumption_status'] == 'plus') {
                    $stock->qty_amount += $ingredient['quantity_amount'];
                } else {
                    $stock->qty_amount -= $ingredient['quantity_amount'];
                }
                $stock->save();
            }
        }

        return response()->json(['message' => 'Stock adjustment was successfully added']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Inventory\StockAdjustmentResource
     *
     * @apiResourceModel App\Models\StockAdjustment
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Inventory\StockAdjustmentResource
     */
    public function show(StockAdjustment $stockAdjustment)
    {
        return new StockAdjustmentResource($stockAdjustment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Category added successfully."
     * }
     * @response status=422 scenario="Unprocessable Content" {
     *     "message": "Server Error."
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
    public function update(StockAdjustmentRequest $request, StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->update([
            'staff_id' => $request->person,
            'date' => $request->date,
            'note' => $request->note,
            'added_by' => auth()->user()->name,
        ]);

        foreach ($request->ingredients as $ingredient) {

            $adjustmentItem = StockAdjustmentItem::where('stock_adjustment_id', $stockAdjustment->id)->where('ingredient_id', $ingredient['id'])->first();

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
                        'quantity_amount' => $request['quantity_amount'],
                        'consumption_status' => $request['consumption_status'],
                    ]);

                    // Plus/Minus stock quantity
                    if ($ingredient['consumption_status'] == 'plus') {
                        $stock->qty_amount += $ingredient['quantity_amount'];
                    } else {
                        $stock->qty_amount -= $ingredient['quantity_amount'];
                    }
                    $stock->save();
                }
            } else {
                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $stockAdjustment->id,
                    'ingredient_id' => $ingredient['id'],
                    'quantity_amount' => $ingredient['quantity_amount'],
                    'consumption_status' => $ingredient['consumption_status'],
                ]);

                $stock = Stock::where('ingredient_id', $ingredient['id'])->first();
                if ($stock) {
                    if ($ingredient['consumption_status'] == 'plus') {
                        $stock->qty_amount += $ingredient['quantity_amount'];
                    } else {
                        $stock->qty_amount -= $ingredient['quantity_amount'];
                    }
                    $stock->save();
                }
            }
        }

        return response()->json(['message' => 'Stock adjustment was successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Stock adjustment deleted successfully."
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
    public function destroy(StockAdjustment $stockAdjustment)
    {
        foreach ($stockAdjustment->items as $item) {
            $stock = Stock::where('ingredient_id', $item->ingredient_id)->first();
            if ($stock) {
                if ($item->consumption_status == 'plus') {
                    $stock->qty_amount -= $item->quantity_amount;
                } else {
                    $stock->qty_amount += $item->quantity_amount;
                }
                $stock->save();
            }
        }
        $stockAdjustment->delete();

        return response()->json(['message' => 'Stock adjustment deleted successfully.']);
    }

    /**
     * Remove the specified resource items from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Stock adjustment item deleted successfully."
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
    public function itemDestroy(StockAdjustmentItem $stockAdjustmentItem)
    {
        $stock = Stock::where('ingredient_id', $stockAdjustmentItem->ingredient_id)->firstOrFail();
        if ($stockAdjustmentItem->consumption_status == 'plus') {
            $stock->qty_amount -= $stockAdjustmentItem->quantity_amount;
        } else {
            $stock->qty_amount += $stockAdjustmentItem->quantity_amount;
        }
        $stock->save();
        $stockAdjustmentItem->delete();

        return response()->json(['message' => 'Stock adjustment item deleted successfully']);
    }
}
