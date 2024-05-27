<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Api\Backend\Inventory\WasteController as ApiWasteController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\WasteRequest;
use App\Models\Food;
use App\Models\Staff;
use App\Models\Waste;
use Yajra\DataTables\Facades\DataTables;

class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_waste');
        if (request()->ajax()) {

            $wastes = Waste::query()->with('staff:id,name');

            return DataTables::eloquent($wastes)
                ->addIndexColumn()
                ->editColumn('date', function ($data) {
                    return format_date($data->date);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('waste.edit', $data->id), 'type' => 'show', 'id' => false, 'can' => 'show_waste'],
                        ['url' => route('waste.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_waste'],
                        ['url' => route('waste.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_waste'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.inventory.waste.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_waste');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $foods = Food::active()->latest('id')->get(['id', 'name']);

        return view('pages.inventory.waste.form', compact('persons', 'foods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WasteRequest $request)
    {
        $this->authorize('create_waste');

        $waste = new Waste();

        $this->updateOrCreate($request, $waste);

        return response()->json(['message' => 'Waste was successfully added']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Waste $waste)
    {
        $this->authorize('show_waste');
        $waste->load('staff:id,name');

        return view('pages.inventory.waste.show', compact('waste'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Waste $waste)
    {
        $this->authorize('edit_waste');
        $waste->load('staff:id,name');
        $persons = Staff::active()->latest('id')->get(['id', 'name']);
        $foods = Food::active()->latest('id')->get(['id', 'name']);

        return view('pages.inventory.waste.form', compact('persons', 'foods', 'waste'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WasteRequest $request, Waste $waste)
    {
        $this->authorize('edit_waste');

        $this->updateOrCreate($request, $waste);

        return response()->json(['message' => 'Waste was successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Waste $waste)
    {
        $this->authorize('delete_waste');
        $api = new ApiWasteController;

        return $api->destroy($waste);
    }

    /**
     * food
     */
    public function food($id)
    {
        $food = Food::findOrFail($id);

        return $food;
    }

    /**
     * updateOrCreate
     */
    public function updateOrCreate($request, Waste $waste)
    {
        $items = [];
        $total_loss = 0;
        foreach ($request->food_id as $key => $food_id) {
            $food = Food::find($food_id);
            if ($food) {
                $items[$key]['food_id'] = $food->id;
                $items[$key]['food_name'] = $food->name;
                $items[$key]['price'] = $food->price;
                $items[$key]['quantity'] = $request->quantity[$key];
                $total = $food->price * $request->quantity[$key];
                $items[$key]['total'] = $total;

                $total_loss += $total;
            }
        }

        $waste->staff_id = $request->person;
        $waste->date = $request->date;
        $waste->note = $request->note;
        $waste->added_by = auth()->user()->name;
        $waste->total_loss = $total_loss;
        $waste->items = json_encode($items);

        if (request()->isMethod('POST')) {
            $waste->save();
        } else {
            $waste->update();
        }

        if (request()->isMethod('POST')) {
            $waste->reference_no = sprintf('%s%05s', '', $waste->id);
            $waste->save();
        }

        return true;
    }
}
