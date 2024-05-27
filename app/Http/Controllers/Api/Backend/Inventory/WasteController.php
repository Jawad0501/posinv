<?php

namespace App\Http\Controllers\Api\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\WasteRequest;
use App\Http\Resources\Backend\Inventory\WasteCollection;
use App\Http\Resources\Backend\Inventory\WasteResource;
use App\Models\Food;
use App\Models\Waste;
use Illuminate\Http\Request;

/**
 * @group Waste Management
 *
 * APIs to Waste
 */
class WasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Inventory\WasteCollection
     *
     * @apiResourceModel App\Models\Waste
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
        $wastes = Waste::query()->with('staff:id,name');

        if ($request->has('keyword')) {
            $wastes = $wastes->where('reference_no', 'like', "%$request->keyword%")
                ->orWhere('date', 'name', 'like', "%$request->keyword%")
                ->orWhere('note', 'code', 'like', "%$request->keyword%")
                ->orWhere('added_by', 'alert_qty', 'like', "%$request->keyword%")
                ->orWhere('total_loss', 'alert_qty', 'like', "%$request->keyword%")
                ->orWhereRelation('staff', 'name', 'like', "%$request->keyword%");
        }
        $wastes = $wastes->latest('id')->paginate();

        return new WasteCollection($wastes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "'Waste was successfully added."
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
    public function store(WasteRequest $request)
    {
        $waste = new Waste();

        $this->updateOrCreate($request, $waste);

        return response()->json(['message' => 'Waste was successfully added']);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Inventory\WasteResource
     *
     * @apiResourceModel App\Models\Waste
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \App\Http\Resources\Backend\Inventory\WasteResource
     */
    public function show(Waste $Waste)
    {
        return new WasteResource($Waste);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Waste was successfully updated."
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
    public function update(WasteRequest $request, Waste $waste)
    {
        $this->updateOrCreate($request, $waste);

        return response()->json(['message' => 'Waste was successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Waste deleted successfully."
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
    public function destroy(Waste $waste)
    {
        $waste->delete();

        return response()->json(['message' => 'Waste deleted successfully']);
    }

    /**
     * updateOrCreate
     *
     * @param  mixed  $request
     * @param  mixed  $waste
     * @return void
     */
    public function updateOrCreate($request, Waste $waste)
    {
        $items = [];
        $total_loss = 0;
        foreach ($request->foods as $key => $reqFood) {
            $food = Food::find($reqFood['id']);
            if ($food) {
                $items[$key]['food_id'] = $food->id;
                $items[$key]['food_name'] = $food->name;
                $items[$key]['price'] = $food->price;
                $items[$key]['quantity'] = $reqFood['quantity'];
                $total = $food->price * $reqFood['quantity'];
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

    }
}
