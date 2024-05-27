<?php

namespace App\Http\Controllers\Backend\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Payroll\LeaveRequest;
use Exception;
use XeroAPI\XeroPHP\Models\PayrollUk\EmployeeLeave;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_payroll');
        if (request()->ajax()) {
            $this->defineApiInstance();
            try {
                $result = $this->apiInstance->getEmployeeLeaves($this->tenant_id, request()->get('employee'));
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $result = json_decode($result);

            return view('pages.payroll.leave.table', compact('result'));
        }

        return view('pages.payroll.leave.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_payroll');
        $this->defineApiInstance();
        try {
            $result = $this->apiInstance->getEmployeeLeaveTypes($this->tenant_id, request()->get('employee'));
        } catch (Exception $e) {
            echo 'Exception when calling PayrollUkApi->getEmployeeLeaveTypes: ', $e->getMessage(), PHP_EOL;
        }
        $result = json_decode($result);

        return view('pages.payroll.leave.form', ['leaveTypes' => $result->leaveTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request)
    {
        return $this->updateOrCreate($request);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize('show_payroll');
        $this->defineApiInstance();
        try {
            $result = $this->apiInstance->getEmployeeLeave($this->tenant_id, request()->get('employee'), $id);
            $result = json_decode($result);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.leave.show', ['leave' => $result->leave]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('edit_payroll');
        $this->defineApiInstance();

        try {
            $result = $this->apiInstance->getEmployeeLeave($this->tenant_id, request()->get('employee'), $id);
            $result = json_decode($result);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        try {
            $leaveTypes = $this->apiInstance->getEmployeeLeaveTypes($this->tenant_id, request()->get('employee'));
            $leaveTypes = json_decode($leaveTypes);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.leave.form', [
            'leave' => $result->leave,
            'leaveTypes' => $leaveTypes->leaveTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeaveRequest $request, $id)
    {
        $this->authorize('edit_payroll');

        return $this->updateOrCreate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->defineApiInstance();
        try {
            $this->apiInstance->deleteEmployeeLeave($this->tenant_id, request()->get('employee'), $id);

            return response()->json(['message' => 'Leave deleted successfully.']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }
    }

    /**
     * update Or Create Employee
     */
    public function updateOrCreate($request, $id = null)
    {
        $this->defineApiInstance();

        $employeeLeave = new EmployeeLeave;
        $employeeLeave->setLeaveTypeID($request->type);
        $employeeLeave->setDescription($request->description);
        $employeeLeave->setStartDate($request->start_date);
        $employeeLeave->setEndDate($request->end_date);
        $employeeLeave->setPeriods($request->pay_period);

        try {
            if ($id != null) {

                $this->apiInstance->updateEmployeeLeave($this->tenant_id, $request->employee, $id, $employeeLeave);
                $msg = 'Leave updated successfully';
            } else {
                $this->apiInstance->createEmployeeLeave($this->tenant_id, $request->employee, $employeeLeave);
                $msg = 'Leave created successfully';
            }

            return response()->json(['message' => $msg]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }
    }
}
