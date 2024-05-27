<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('show_stock');
        if (request()->ajax()) {

            $stocks = Stock::query()->with('ingredient:id,category_id,unit_id,name,alert_qty,code', 'ingredient.unit:id,name', 'ingredient.category:id,name', 'ingredient.stock:id,ingredient_id,qty_amount');

            return DataTables::eloquent($stocks)
                ->addIndexColumn()
                ->addColumn('ingredient_name', function ($data) {
                    return "{$data->ingredient->name}";
                })
                ->addColumn('alert_qty', function ($data) {
                    if ($data->ingredient->stock->qty_amount < $data->ingredient->alert_qty) {
                        return "<span class='text-danger'>{$data->ingredient->alert_qty} {$data->ingredient->unit->name}</span>";
                    }

                    return "{$data->ingredient->alert_qty} {$data->ingredient->unit->name}";
                })
                ->addColumn('stock_qty', function ($data) {
                    return "{$data->ingredient->stock->qty_amount} {$data->ingredient->unit->name}";
                })
                ->rawColumns(['ingredient_name', 'alert_qty', 'stock_qty'])
                ->toJson();
        }

        return view('pages.inventory.stock');
    }
}
