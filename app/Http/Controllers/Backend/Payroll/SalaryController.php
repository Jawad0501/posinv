<?php

namespace App\Http\Controllers\Backend\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Payroll\SalaryRequest;
use Exception;
use XeroAPI\XeroPHP\Models\PayrollUk\SalaryAndWage;

class SalaryController extends Controller
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
                $result = $this->apiInstance->getEmployeeSalaryAndWages($this->tenant_id, request()->get('employee'));
            } catch (Exception $e) {
                return $e->getMessage();
            }
            $result = json_decode($result);

            return view('pages.payroll.salary.table', compact('result'));
        }

        return view('pages.payroll.salary.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_payroll');
        $this->defineApiInstance();

        try {
            $result = $this->apiInstance->getEarningsRates($this->tenant_id);
            $result = json_decode($result);
        } catch (Exception $e) {
            dd($e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.salary.form', ['earningsRates' => $result->earningsRates]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalaryRequest $request)
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
            $result = $this->apiInstance->getEmployeeSalaryAndWage($this->tenant_id, request()->get('employee'), $id);
            $result = json_decode($result);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.leave.show', ['salary' => $result->salaryAndWages[0]]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('edit_payroll');
        $this->defineApiInstance();

        try {
            $result = $this->apiInstance->getEmployeeSalaryAndWage($this->tenant_id, request()->get('employee'), $id);
            $result = json_decode($result);

            $earningsRates = $this->apiInstance->getEarningsRates($this->tenant_id);
            $earningsRates = json_decode($earningsRates);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong, please try again',
                'error' => $e->getMessage(),
            ], 300);
        }

        return view('pages.payroll.salary.form', [
            'salary' => $result->salaryAndWages[0],
            'earningsRates' => $earningsRates->earningsRates,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalaryRequest $request, $id)
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
            $this->apiInstance->deleteEmployeeSalaryAndWage($this->tenant_id, request()->get('employee'), $id);

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

        $salaryAndWage = new SalaryAndWage;
        $salaryAndWage->setEarningsRateID($request->earning_rate);
        $salaryAndWage->setNumberOfUnitsPerDay($request->unit_per_day);
        $salaryAndWage->setNumberOfUnitsPerWeek($request->unit_per_week);
        $salaryAndWage->setAnnualSalary($request->annual_salary);
        $salaryAndWage->setEffectiveFrom($request->effective_from);
        $salaryAndWage->setPaymentType('Salary');
        $salaryAndWage->setStatus('Active');

        try {
            if ($id != null) {
                $this->apiInstance->updateEmployeeSalaryAndWage($this->tenant_id, $request->employee, $id, $salaryAndWage);
                $msg = 'Leave updated successfully';
            } else {
                $this->apiInstance->createEmployeeSalaryAndWage($this->tenant_id, $request->employee, $salaryAndWage);
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
