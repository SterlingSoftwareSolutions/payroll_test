<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\department;
use App\Models\Payslip;
use App\Models\SalaryDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class PayslipController extends Controller
{
    public function index()
    {
        $date = now()->startOfMonth()->subMonth();
        $payslips = Payslip::whereDate('date', $date)->get();
        return view('reports/payslip-approve', compact(['payslips']));
    }

    public function show(Payslip $payslip)
    {
        $payslipdata = $payslip->attributesToArray();
        $payslipdata['employee_employee_id'] = $payslip->employee->employee_id;
        $payslipdata['net_salary'] = $payslip->net_salary();
        return response()->json($payslipdata);
    }

    public function update(Request $request)
    {
        $payslip = Payslip::findOrFail($request->payslip_id);
        $validated = $request->validate([
              "basic_salary" => 'required',
              "br_allowance" => 'required',
              "fixed_allowance" => 'required',
              "attendance_allowance" => 'required',
              "holiday_payment" => 'required',
              "incentives" => 'required',
              "ot" => 'required',
              "other_increments" => 'required',
              "no_pay_leave_deduction" => 'required',
              "late_deduction" => 'required',
              "employee_epf" => 'required',
              "paye" => 'required',
              "stamp_duty" => 'required',
              "advance" => 'required',
              "loan" => 'required',
              "other_deductions" => 'required',
              "company_epf" => 'required',
              "etf" => 'required',
        ]);
        $validated['approved_at'] = now();
        $payslip->update($validated);
        return back();
    }

    public function print(Payslip $payslip)
    {
        $pdf = Pdf::loadView('payslip_pdf', compact('payslip'))->setPaper('a4', 'portrait');;
        return $pdf->download(strtoupper(preg_split('#\s+#', $payslip->employee->full_name)[0]));
    }

    public function create_payslip(Employee $employee)
    {
        $basic_salary = $employee->basic_Salary;

        // Increments
        $br_allowance = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'BR Allowance')
            ->where('type', 'increments')
            ->sum('increment_amount');

        $fixed_allowance = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Fixed Allowance')
            ->where('type', 'increments')
            ->sum('increment_amount');

        $attendance_allowance = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Attendance Allowance')
            ->where('type', 'increments')
            ->sum('increment_amount');

        $incentives = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'like', 'Incentive %')
            ->where('type', 'increments')
            ->sum('increment_amount');

        $other_incrmeents = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Other')
            ->where('type', 'increments')
            ->sum('increment_amount');

        // Deductions
        $advance = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Advance')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $loan = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Loan')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $other_deductions = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Other')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        // Salary amounts
        $gross_salary = $basic_salary + $br_allowance + $fixed_allowance;
        $gross_salary_day = $gross_salary / 30;
        $gross_salary_hour = $gross_salary_day / 10;

        // Attendance data
        $attandance_data = $employee->attendance_data();

        // Overtime
        $ot_hours = 0;
        $ot = ($gross_salary / 240) * $ot_hours;

        // No pay leave deduction
        $no_pay_leaves = $attandance_data['absent_days'];
        $no_pay_leave_deduction =  $gross_salary_day * $no_pay_leaves;

        // Late hours deduction
        $late_hours = 0;
        $late_deduction = $gross_salary_hour * $late_hours;

        // Total basic pay
        $total_basic_pay = $gross_salary  - $no_pay_leave_deduction - $late_deduction;

        // Employee EPF
        $employee_epf = ($total_basic_pay / 100) * 8;

        // Company EPF/ETF
        $company_epf = ($total_basic_pay / 100) * 12;
        $etf = ($total_basic_pay / 100) * 3;

        $payslip = Payslip::firstOrCreate([
            'employee_id' => $employee->id,
            'date' => now()->startOfMonth()->subMonth(),
        ],[
            'approved_at' => null,

            'basic_salary' => $basic_salary,
            'br_allowance' => $br_allowance,
            'fixed_allowance' => $fixed_allowance,
            'attendance_allowance' => $attendance_allowance,

            'no_pay_leave_deduction' => $no_pay_leave_deduction,
            'late_deduction' => $late_deduction,

            'employee_epf' => $employee_epf,
            'paye' => 0,
            'stamp_duty' => 0,

            'advance' => $advance,
            'loan' => $loan,
            'other_deductions' => $other_deductions,

            'holiday_payment' => 0,
            'incentives' => $incentives,
            'ot' => $ot,
            'other_increments' => $other_incrmeents,

            'company_epf' => $company_epf,
            'etf' => $etf,

            'account_name' => $employee->account_name,
            'account_number' => $employee->account_number,
            'bank_name' => $employee->bank_name,
            'branch' => $employee->branch,
        ]);

        // Deactivate one-time adjustments
        SalaryDetail::where('employee_id', $employee->employee_id)
                    ->where('active', true)
                    ->where('recurring', false)
                    ->update(['active' => false]);

        return $payslip;
    }

    // Generate payslips for current month
    public function generate_payslips()
    {
        $employees = Employee::where('status', 'active');
        $employees->each(function ($employee){
            $this->create_payslip($employee);
        });
        return redirect('/form/payslip/approve');
    }

    public function get_salary_report()
    {

        $query = Employee::query();
        $employees = $query->get();

        $departments = department::all();
        return view('reports/salary-report', compact('departments', 'employees'));
    }

    public function getDetails($employeeId)
    {
        $employee = Employee::where('employee_id', $employeeId)->first();
        $basicSalary = $employee->basic_Salary;

        $brAllowance = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'BR allowance')
            ->where('type', 'increments')
            ->sum('increment_amount');
        
        $incentive1 = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Incentive 1')
            ->where('type', 'increments')
            ->sum('increment_amount');
        
        $incentive2 = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Incentive 2')
            ->where('type', 'increments')
            ->sum('increment_amount');
        
        $increment_others = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Others')
            ->where('type', 'increments')
            ->sum('increment_amount');
        
        $bodim = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Bodim')
            ->where('type', 'deductions')
            ->sum('increment_amount');
        
        $Others = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Others')
            ->where('type', 'deductions')
            ->sum('increment_amount');
        
        $deduction_others = $bodim + $Others;
        
        $Advanced = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Advanced')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        return response()->json([
            'basic_salary' => $basicSalary, 'brAllowance' => $brAllowance,
            'incentive1' => $incentive1, 'incentive2' => $incentive2, 'increment_others' => $increment_others,
            'deduction_others' => $deduction_others, 'Advanced' => $Advanced
        ]);
    }
}