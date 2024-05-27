<?php

namespace App\Http\Controllers\Api\Backend\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\PurchaseRequest;
use App\Http\Resources\Backend\Finance\PurchaseCollection;
use App\Http\Resources\Backend\Finance\PurchaseResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use Illuminate\Http\Request;

/**
 * @group Purchase Management
 *
 * APIs to Purchase
 */
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Finance\PurchaseCollection
     *
     * @apiResourceModel App\Models\Purchase
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
        $purchases = Purchase::query()->with('supplier:id,name', 'bank:id,name');

        if ($request->has('keyword')) {
            $purchases = $purchases->where('reference_no', 'like', "%$request->keyword%")
                ->orWhere('total_amount', 'like', "%$request->keyword%")
                ->orWhere('shipping_charge', 'like', "%$request->keyword%")
                ->orWhere('discount_amount', 'like', "%$request->keyword%")
                ->orWhere('paid_amount', 'like', "%$request->keyword%")
                ->orWhere('date', 'like', "%$request->keyword%")
                ->orWhere('payment_type', 'like', "%$request->keyword%")
                ->orWhere('details', 'like', "%$request->keyword%")
                ->orWhereRelation('supplier', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('bank', 'name', 'like', "%$request->keyword%");
        }
        $purchases = $purchases->latest('id')->paginate();

        return new PurchaseCollection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Purchase added successfully."
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
     */
    public function store(PurchaseRequest $request)
    {
        $purchase = Purchase::create([
            'supplier_id' => $request->supplier,
            'bank_id' => $request->payment_type == Purchase::PAYMENT_TYPE_BANK ? $request->bank : null,
            'total_amount' => $request->total_amount,
            'shipping_charge' => $request->shipping_charge,
            'discount_amount' => $request->discount,
            'paid_amount' => $request->paid,
            'date' => $request->purchase_date,
            'details' => $request->details,
            'payment_type' => $request->payment_type,
            'status' => true,
        ]);

        $purchase->reference_no = sprintf('%s%05s', '', $purchase->id);
        $purchase->save();

        foreach ($request->ingredients as $ingredient) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'ingredient_id' => $ingredient['id'],
                'unit_price' => $ingredient['unit_price'],
                'quantity_amount' => $ingredient['quantity_amount'],
                'total' => $ingredient['quantity_amount'] * $ingredient['unit_price'],
                'expire_date' => $ingredient['expire_date'],
            ]);

            $stock = Stock::where('ingredient_id', $ingredient['id'])->first();
            if ($stock) {
                $stock->qty_amount += $ingredient['quantity_amount'];
                $stock->save();
            } else {
                Stock::create([
                    'ingredient_id' => $ingredient['id'],
                    'qty_amount' => $ingredient['quantity_amount'],
                ]);
            }
        }

        return response()->json(['message' => 'Purchase created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Finance\PurchaseResource
     *
     * @apiResourceModel App\Models\Purchase
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(Purchase $purchase)
    {
        return new PurchaseResource($purchase->load('items'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Purchase updated successfully."
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
     */
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        foreach ($request->ingredients as $ingredient) {

            $purchaseItem = PurchaseItem::where('purchase_id', $purchase->id)->where('ingredient_id', $ingredient['id'])->first();

            if ($purchaseItem) {
                // Decrement stock quantity
                $stock = Stock::where('ingredient_id', $purchaseItem->ingredient_id)->first();
                $stock->qty_amount -= $purchaseItem->quantity_amount;
                $stock->save();

                // Update Purchase Item
                $purchaseItem->update([
                    'unit_price' => $ingredient['unit_price'],
                    'quantity_amount' => $ingredient['quantity_amount'],
                    'expire_date' => $ingredient['expire_date'],
                    'total' => $ingredient['quantity_amount'] * $ingredient['unit_price'],
                ]);

                // Add stock quantity
                $stock->qty_amount += $ingredient['quantity_amount'];
                $stock->save();
            } else {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'ingredient_id' => $ingredient['id'],
                    'unit_price' => $ingredient['unit_price'],
                    'quantity_amount' => $ingredient['quantity_amount'],
                    'expire_date' => $ingredient['expire_date'],
                    'total' => $ingredient['quantity_amount'] * $ingredient['unit_price'],
                ]);

                $stock = Stock::where('ingredient_id', $ingredient['id'])->first();
                if ($stock) {
                    $stock->qty_amount += $ingredient['quantity_amount'];
                    $stock->save();
                } else {
                    Stock::create([
                        'ingredient_id' => $ingredient['id'],
                        'qty_amount' => $ingredient['quantity_amount'],
                    ]);
                }
            }
        }

        $purchase->update([
            'supplier_id' => $request->supplier,
            'bank_id' => $request->payment_type == Purchase::PAYMENT_TYPE_BANK ? $request->bank : null,
            'total_amount' => $request->total_amount,
            'shipping_charge' => $request->shipping_charge,
            'discount_amount' => $request->discount,
            'paid_amount' => $request->paid,
            'date' => $request->purchase_date,
            'payment_type' => $request->payment_type,
            'details' => $request->details,
        ]);

        return response()->json(['message' => 'Purchase updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Purchase deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(Purchase $purchase)
    {
        foreach ($purchase->items as $item) {
            $stock = Stock::where('ingredient_id', $item->ingredient_id)->first();
            if ($stock) {
                $stock->qty_amount -= $item->quantity_amount;
                $stock->save();
            }
        }
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted successfully']);
    }

    /**
     * Remove the specified resource items from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Purchase item deleted successfully."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function itemDestroy(PurchaseItem $purchaseItem)
    {
        $stock = Stock::where('ingredient_id', $purchaseItem->ingredient_id)->firstOrFail();
        $stock->qty_amount -= $purchaseItem->quantity_amount;
        $stock->save();
        $purchaseItem->delete();

        return response()->json(['message' => 'Purchase item deleted successfully']);
    }
}
