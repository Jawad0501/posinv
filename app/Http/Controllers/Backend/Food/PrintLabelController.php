<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Controller;
use App\Models\Barcode;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintLabelController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $barcodes = Barcode::query()->get();

        return view('pages.food.label.create', compact('barcodes'));
    }

    /**
     * search menu
     */
    public function menu(Request $request)
    {
        if ($request->has('keyword')) {
            $menus = collect(DB::table('food')->where('name', 'like', "%$request->keyword%")->select('id', 'name')->get()->toArray())->flatten()->all();

            return $menus;
        }
        $menu = Food::query()->with('variants:id,food_id,name')->find($request->id);

        return view('pages.food.label.product-row', compact('menu'));
    }

    /**
     * search menu
     */
    public function preview(Request $request)
    {
        $barcode_details = Barcode::find($request->barcode_setting);
        $barcode_details->stickers_in_one_sheet = $barcode_details->is_continuous ? $barcode_details->stickers_in_one_row : $barcode_details->stickers_in_one_sheet;
        $barcode_details->paper_height = $barcode_details->is_continuous ? $barcode_details->height : $barcode_details->paper_height;
        if ($barcode_details->stickers_in_one_row == 1) {
            $barcode_details->col_distance = 0;
            $barcode_details->row_distance = 0;
        }

        $menus = [];
        foreach ($request->products as $product) {
            $menu = Food::query()->with('variants')->find($product['id']);
            $variant = $menu->variants->where('id', $product['variant'])->first();

            for ($i = 1; $i <= $product['no_of_labels']; $i++) {
                $menus[] = (object) [
                    'name' => $menu->name,
                    'variant' => (object) [
                        'name' => $variant->name,
                        'price' => $variant->price,
                        'sub_sku' => $variant->sub_sku,
                    ],
                ];
            }
        }

        $margin_top = $barcode_details->is_continuous ? 0 : $barcode_details->top_margin * 1;
        $margin_left = $barcode_details->is_continuous ? 0 : $barcode_details->left_margin * 1;
        $paper_width = $barcode_details->paper_width * 1;
        $paper_height = $barcode_details->paper_height * 1;

        return view('pages.food.label.preview', compact('barcode_details', 'margin_top', 'margin_left', 'paper_width', 'paper_height', 'menus'));

        //return $output;
    }
}
