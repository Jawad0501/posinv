<?php

namespace App\Http\Controllers\Api\Backend\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TableLayoutRequest;
use App\Http\Resources\Backend\Restaurant\TablelayoutCollection;
use App\Http\Resources\Backend\Restaurant\TableLayoutResource;
use App\Models\TableLayout;
use Illuminate\Http\Request;

/**
 * @group Restaurant Table Layout Management
 *
 * APIs to Table Layout
 */
class TableLayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Backend\Restaurant\TableLayoutCollection
     *
     * @apiResourceModel App\Models\TableLayout
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
        $tables = TableLayout::query();
        if ($request->has('keyword')) {
            $tables = $tables->whereLike(['name', 'number', 'capacity', 'available', 'status'], $request->keyword);
        }
        $tables = $tables->latest('id')->paginate();

        return new TableLayoutCollection($tables);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'New table added successfully.'
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
    public function store(TableLayoutRequest $request)
    {
        return $request->saved();
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Backend\Restaurant\TableLayoutResource
     *
     * @apiResourceModel App\Models\TableLayout
     *
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function show(TableLayout $tableLayout)
    {
        return new TableLayoutResource($tableLayout);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Table updated successfully.'
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
    public function update(TableLayoutRequest $request, TableLayout $tableLayout)
    {
        return $request->saved($tableLayout);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response 200 {
     *     'message': 'Table deleted successfully.'
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function destroy(TableLayout $tableLayout)
    {
        delete_uploaded_file($tableLayout->image);
        $tableLayout->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }
}
