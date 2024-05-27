<?php

namespace App\Http\Controllers\Backend\Food;

use App\Http\Controllers\Api\Backend\Food\AddonController as ApiAddonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AddonRequest;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('show_addon');
        if ($request->ajax()) {
            return DataTables::of(Addon::query()->with('addon:id,name'))
                ->addIndexColumn()
                ->addColumn('addon', fn ($data) => $data->addon?->name)
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('food.addon.edit', $data->id), 'type' => 'edit', 'can' => 'edit_addon'],
                        ['url' => route('food.addon.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_addon'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.food.addon.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_addon');
        $addons = DB::table('addons')->whereNull('parent_id')->orderBy('name')->get();

        return view('pages.food.addon.form', compact('addons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddonRequest $request)
    {
        $this->authorize('create_addon');

        return $request->saved();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Addon $addon)
    {
        $this->authorize('edit_addon');
        $addons = DB::table('addons')->whereNull('parent_id')->orderBy('name')->get();

        return view('pages.food.addon.form', compact('addon', 'addons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddonRequest $request, Addon $addon)
    {
        $this->authorize('edit_addon');

        return $request->saved($addon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Addon $addon)
    {
        $this->authorize('delete_addon');
        $api = new ApiAddonController;

        return $api->destroy($addon);
    }
}
