<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\MealPeriodController as ApiMealPeriodController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\MealPeriodRequest;
use App\Models\MealPeriod;
use Yajra\DataTables\Facades\DataTables;

class MealPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_meal_period');
        if (request()->ajax()) {
            return DataTables::of(MealPeriod::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.meal-period.edit', $data->id), 'type' => 'edit', 'can' => 'edit_meal_period'],
                        ['url' => route('food.meal-period.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_meal_period'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.meal-period.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_meal_period');

        return view('pages.food.meal-period.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MealPeriodRequest $request)
    {
        $this->authorize('create_meal_period');

        return $request->saved();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MealPeriod $mealPeriod)
    {
        $this->authorize('edit_meal_period');

        return view('pages.food.meal-period.form', compact('mealPeriod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MealPeriodRequest $request, MealPeriod $mealPeriod)
    {
        $this->authorize('edit_meal_period');

        return $request->saved($mealPeriod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealPeriod $mealPeriod)
    {
        $this->authorize('delete_meal_period');
        $api = new ApiMealPeriodController;

        return $api->destroy($mealPeriod);
    }
}
