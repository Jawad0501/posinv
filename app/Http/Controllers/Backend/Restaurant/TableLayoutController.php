<?php

namespace App\Http\Controllers\Backend\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TableLayoutRequest;
use App\Models\Category;
use App\Models\Tablelayout;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class TableLayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_table');
        if (request()->ajax()) {
            return DataTables::of(Tablelayout::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        // ['url' => route('generate-qr-code', $data->id), 'type' => 'qr', 'can' => 'edit_table'],
                        ['url' => route('table-layout.edit', $data->id), 'type' => 'edit', 'can' => 'edit_table'],
                        ['url' => route('table-layout.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_table'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.restaurant.table-layout.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_table');
        $categories = Category::all();

        return view('pages.restaurant.table-layout.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TableLayoutRequest $request)
    {
        $this->authorize('create_table');

        return $request->saved();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tablelayout $tableLayout)
    {
        $this->authorize('edit_table');
        $categories = Category::all();

        return view('pages.restaurant.table-layout.form', compact('tableLayout', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TableLayoutRequest $request, Tablelayout $tableLayout)
    {
        $this->authorize('edit_table');

        return $request->saved($tableLayout);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tablelayout $tableLayout)
    {
        $this->authorize('delete_table');
        delete_uploaded_file($tableLayout->image);
        delete_uploaded_file($tableLayout->qr_code);
        $tableLayout->delete();

        return response()->json(['message' => 'Table deleted successfully']);
    }

    /**
     * Show table list csetting
     */
    public function setting()
    {
        $this->authorize('show_table');
        $table_layouts = Tablelayout::get(['id', 'image']);

        return view('pages.restaurant.table-layout.setting', compact('table_layouts'));
    }

    /**
     * Show category wise food in form
     */
    public function getCategoryFood($category)
    {
        $category = Category::find($category);
        $foods = $category->foods()->get();

        return response()->json(['foods' => $foods]);
    }

    public function generateQR($tableLayout)
    {

        $this->authorize('edit_table');
        $tableLayout = Tablelayout::where('id', $tableLayout)->firstOrFail();
        $categories = Category::all();

        return view('pages.restaurant.table-layout.generate-qr', compact('tableLayout', 'categories'));
    }

    public function generateQrImage($tableLayout, $category)
    {

        $tableLayout = Tablelayout::where('number', $tableLayout)->firstOrFail();

        if (Storage::disk('public')->exists('table-qr/'.$tableLayout->number.'.svg')) {
            Storage::disk('public')->delete('table-qr/'.$tableLayout->number.'.svg');
        }

        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        QrCode::generate(route('table-menu', [$tableLayout->number, $category]), 'storage/table-qr/'.$tableLayout->number.'.svg');
        $qr_url = 'table-qr/'.$tableLayout->number.'.svg';

        return response()->json(['message' => $qr_url]);

        // if(Storage::disk('public')->exists('table-qr/'.$tableLayout->number.'.svg')){
        //     Storage::disk('public')->delete('table-qr/'.$tableLayout->number.'.svg');
        // }

        // QrCode::generate(route('table-menu', $tableLayout->number), 'storage/table-qr/'.$tableLayout->number.'.svg');
        // $qr_url = 'table-qr/'.$tableLayout->number.'.svg';

        // return response()->json(['message' => $qr_url]);
    }
}
