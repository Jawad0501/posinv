<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\AllergyController as ApiAllergyController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AllergyRequest;
use App\Models\Allergy;
use Yajra\DataTables\Facades\DataTables;

class AllergyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_allergy');
        if (request()->ajax()) {
            return DataTables::of(Allergy::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.allergy.edit', $data->id), 'type' => 'edit', 'can' => 'edit_allergy'],
                        ['url' => route('food.allergy.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_allergy'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.allergy.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_allergy');

        return view('pages.food.allergy.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AllergyRequest $request)
    {
        $this->authorize('create_allergy');

        $api = new ApiAllergyController;

        return $api->store($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allergy $allergy)
    {
        $this->authorize('edit_allergy');

        return view('pages.food.allergy.form', compact('allergy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AllergyRequest $request, Allergy $allergy)
    {
        $this->authorize('edit_allergy');

        $api = new ApiAllergyController;

        return $api->update($request, $allergy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allergy $allergy)
    {
        $this->authorize('delete_allergy');
        $api = new ApiAllergyController;

        return $api->destroy($allergy);
    }
}
