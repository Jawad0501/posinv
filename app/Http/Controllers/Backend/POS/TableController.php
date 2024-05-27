<?php

namespace App\Http\Controllers\Backend\POS;

use App\Http\Controllers\Api\Backend\POS\TableController as ApiTableController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tablelayout;

class TableController extends Controller
{
    public $table;

    /**
     * assigned api table controller
     */
    public function __construct()
    {
        $this->table = new ApiTableController;
    }

    /**
     * show table information
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tables = $this->table->index();

        return view('pages.pos.table.info', compact('tables'));
    }

    /**
     * show table information
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $tables = $this->table->index();

        return view('pages.pos.table.create', compact('tables'));
    }

    /**
     * store table information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
            'tables.*.person' => 'nullable|integer',
            'tables.*.id' => 'nullable|integer|exists:tablelayouts,id',
        ]);

        $tables = [];
        foreach ($request->tables as $table) {
            if (! empty($table['person'])) {
                $tables[] = [
                    'table_id' => $table['id'],
                    'person' => $table['person'],
                ];
            }
        }
        session()->put('tables', $tables);

        return response()->json([
            'tables' => 1,
            'message' => 'Table added successfully',
        ]);
    }


    public function seeAvailability($id){
        $table = Tablelayout::where('id', $id)->first();

        if($table){
            if($table->available > 0){
                return response()->json([
                    'table_available' => true,
                    'table_number' => $table->number
                ]);
            }
            else{
                return response()->json([
                    'table_available' => false,
                    'table_number' => $table->number
                ]); 
            }
        }
    }
}
