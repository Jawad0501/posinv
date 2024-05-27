<?php

namespace App\Http\Controllers\Backend\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StaffRequest;
use App\Models\Role;
use App\Models\Staff;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_staff');
        if (request()->ajax()) {

            $staff = Staff::query()->with('role:id,name');

            return DataTables::eloquent($staff)
                ->addIndexColumn()
                ->editColumn('last_login', function ($data) {
                    return format_date($data->last_login, true);
                })
                ->addColumn('action', function ($data) {
                    return $this->buttonGroup([
                        ['url' => route('staff.show', $data->id), 'type' => 'show', 'can' => 'show_staff'],
                        ['url' => route('staff.edit', $data->id), 'type' => 'edit', 'can' => 'edit_staff'],
                        ['url' => route('staff.destroy', $data->id), 'type' => 'delete', 'can' => 'delete_staff'],
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.hr.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_staff');
        $roles = Role::all();

        return view('pages.hr.staff.form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {
        $this->authorize('create_staff');

        $validation = self::uniqueValidation(['email' => $request->email, 'phone' => $request->phone]);
        if (gettype($validation) !== 'boolean') {
            return $validation;
        }

        $input = $request->all();
        $input['image'] = file_upload($request->image, 'staff');
        $input['password'] = bcrypt($request->password);
        $input['role_id'] = $request->role;

        $staff = Staff::create($input);
        $staff->id_number = generate_invoice($staff->id);
        $staff->save();

        return response()->json(['message' => 'Staff has been added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $this->authorize('show_staff');

        return view('pages.hr.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $this->authorize('edit_staff');
        $roles = Role::all();

        return view('pages.hr.staff.form', compact('roles', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffRequest $request, Staff $staff)
    {
        $this->authorize('edit_staff');

        $validation = self::uniqueValidation(['email' => $request->email, 'phone' => $request->phone], ['email' => $staff->email, 'phone' => $staff->phone]);
        if (gettype($validation) !== 'boolean') {
            return $validation;
        }

        $input = $request->all();
        $input['role_id'] = $request->role;

        if ($request->hasFile('image')) {
            $input['image'] = file_upload($request->image, 'staff', $staff->image);
        }

        $staff->update($input);

        return response()->json(['message' => 'Staff has been updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $this->authorize('delete_supplier');
        delete_uploaded_file($staff->image);
        $staff->delete();

        return response()->json(['message' => 'Staff deleted successfully']);
    }

    /**
     * Unique Validation
     */
    protected static function uniqueValidation(array $request, array $old = [])
    {
        if (! empty($request['email'])) {
            $email = Staff::query();
            if (count($old) <= 0) {
                $email = $email->where('email', $request['email'])->first();
            } else {
                $email = $email->where(function ($query) use ($request, $old) {
                    $query->where('email', $request['email'])->where('email', '!=', $old['email'])->first();
                })
                    ->first();
            }
            if ($email) {
                return response()->json([
                    'errors' => ['email' => ['The email has already been taken.']],
                ], 422);
            }
        }
        if (! empty($request['phone'])) {
            $phone = Staff::query();
            if (count($old) <= 0) {
                $phone = $phone->where('phone', $request['phone'])->first();
            } else {
                $phone = $phone->where(function ($query) use ($request, $old) {
                    $query->where('phone', $request['phone'])->where('phone', '!=', $old['phone'])->first();
                })
                    ->first();
            }
            if ($phone) {
                return response()->json([
                    'errors' => ['phone' => ['The phone has already been taken.']],
                ], 422);
            }
        }

        return true;
    }
}
