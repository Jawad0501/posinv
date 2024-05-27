<?php

namespace App\Http\Controllers\Backend\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RoleRequest;
use App\Models\Module;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_role');
        if (request()->ajax()) {
            return DataTables::eloquent(Role::query())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('role.edit', $data->id), 'type' => 'edit', 'id' => false, 'can' => 'edit_role'],
                        ['url' => route('role.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_role'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.hr.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_role');
        $modules = Module::with('permissions')->orderBy('name', 'asc')->get();

        return view('pages.hr.role.form', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('create_role');

        $input = $request->all();
        $input['slug'] = generate_slug($request->name);

        Role::create($input)->permissions()->sync($request->input('permissions'), []);

        return response()->json(['message' => 'Role created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorize('edit_role');
        $role->load('permissions');
        $modules = Module::with('permissions')->orderBy('name', 'asc')->get();

        return view('pages.hr.role.form', compact('modules', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('edit_role');

        $input = $request->all();
        $input['slug'] = generate_slug($request->name);

        $role->update($input);

        $role->permissions()->sync($request->input('permissions'));

        return response()->json(['message' => 'Role updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete_role');
        if ($role->deletable) {
            $role->load('users');
            foreach ($role->users as $user) {
                $user->delete();
            }
            $role->delete();

            return response()->json(['message' => 'Role deleted successfully']);
        }

        return response()->json(['message' => 'This role does not deletable'], 300);
    }
}
