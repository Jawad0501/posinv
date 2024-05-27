<?php

namespace App\Http\Responses;

use App\Models\Food;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\DB;
use stdClass;

class CreateFoodResponse implements Responsable
{
    public function __construct(private ?Food $food = null)
    {
    }

    public function toResponse($request)
    {
        $data = new stdClass;
        $data->mealPeriods = DB::table('meal_periods')->latest('id')->get(['id', 'name']);
        $data->categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $data->addons = DB::table('addons')->latest('id')->get(['id', 'name']);
        $data->allergies = DB::table('allergies')->latest('id')->get(['id', 'name']);
        $data->ingredients = DB::table('ingredients')->latest('id')->get(['id', 'name']);
        $data->sizes = DB::table('variants')->distinct()->get(['name'])->pluck('name');

        return view('pages.food.item.form', [
            'data' => $data,
            'food' => $this->food,
        ]);
    }
}
