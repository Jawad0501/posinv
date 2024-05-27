<?php

namespace App\Http\Controllers\Backend\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Payroll\EmployeeRequest;
use Exception;
use XeroAPI\XeroPHP\Models\PayrollUk\Address;
use XeroAPI\XeroPHP\Models\PayrollUk\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('show_payroll');
        if (request()->ajax()) {
            $filter = 'isOffPayrollWorker==false';
            $page = request()->has('page') ? request()->get('page') : 1;

            $this->defineApiInstance();
            try {
                $result = $this->apiInstance->getEmployees($this->tenant_id, $filter, $page);
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $result = json_decode($result);

            return view('pages.payroll.employee.table', compact('result'));
        }

        return view('pages.payroll.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_payroll');

        return view('pages.payroll.employee.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $this->authorize('create_payroll');

        dd($request);

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
            $result = $this->apiInstance->getEmployee($this->tenant_id, $id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.employee.show', ['employee' => json_decode($result['employee'])]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('edit_payroll');
        $this->defineApiInstance();
        try {
            $result = $this->apiInstance->getEmployee($this->tenant_id, $id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.employee.form', ['employee' => json_decode($result['employee'])]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, $id)
    {
        $this->authorize('edit_payroll');

        return $this->updateOrCreate($request, $id);
    }

    /**
     * update Or Create Employee
     */
    public function updateOrCreate($request, $id = null)
    {
        $this->defineApiInstance();

        $address = new Address;
        $address->setAddressLine1($request->address);
        $address->setCity($request->city);
        $address->setPostCode($request->post_code);
        $address->setCountryName('UNITED KINGDOM');

        $employee = new Employee;
        $employee->setFirstName($request->first_name);
        $employee->setLastName($request->last_name);
        $employee->setTitle($request->title);
        $employee->setEmail($request->email);
        $employee->setPhoneNumber($request->phone_number);
        $employee->setGender($request->gender);
        $employee->setDateOfBirth($request->date_of_birth);
        $employee->setAddress($address);

        try {
            if ($id != null) {
                $this->apiInstance->updateEmployee($this->tenant_id, $id, $employee);
                $msg = 'Employee updated successfully';
            } else {
                $this->apiInstance->createEmployee($this->tenant_id, $employee);
                $msg = 'Employee created successfully';
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
