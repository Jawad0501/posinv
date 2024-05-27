<?php

namespace App\Http\Controllers\Api\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\Inventory\StockCollection;
use App\Models\Stock;
use Illuminate\Http\Request;

/**
 * @group Stock Management
 *
 * APIs to Stock
 */
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Inventory\StockCollection
     *
     * @apiResourceModel App\Models\Stock
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
    public function __invoke(Request $request)
    {
        $stocks = Stock::query()->with(
            'ingredient:id,category_id,unit_id,name,alert_qty,code',
            'ingredient.unit:id,name',
            'ingredient.category:id,name',
            'ingredient.stock:id,ingredient_id,qty_amount'
        );

        if ($request->has('keyword')) {
            $stocks = $stocks->where('qty_amount', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient', 'code', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient', 'alert_qty', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient.category', 'name', 'like', "%$request->keyword%")
                ->orWhereRelation('ingredient.unit', 'name', 'like', "%$request->keyword%");
        }
        $stocks = $stocks->latest('id')->paginate();

        return new StockCollection($stocks);
    }
}
