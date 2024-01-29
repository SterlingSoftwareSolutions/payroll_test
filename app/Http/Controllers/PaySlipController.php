<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\department;
use App\Models\Payslip;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class PayslipController extends Controller
{
    public function index()
    {
        $date = now()->startOfMonth()->subMonth();
        $payslips = Payslip::whereDate('date', $date)->get();
        return view('reports/payslip-approve', compact(['payslips']));
    }

    public function create_payslip(Employee $employee)
    {
        $payslip = Payslip::firstOrCreate([
            'employee_id' => $employee->id,
            'date' => now()->startOfMonth()->subMonth(),
        ],[
            'approved_at' => now(),
            'basic_salary' => $employee->basic_Salary,
            'br_allowance' => 0,
            'fixed_allowance' => 0,
            'attendance_allowance' => 0,
            'no_pay_leave_deduction' => 0,
            'late_deduction' => 0,
            'employee_epf' => 0,
            'paye' => 0,
            'stamp_duty' => 0,
            'advance' => 0,
            'loan' => 0,
            'holiday_payment' => 0,
            'incentives' => 0,
            'ot' => 0,
            'company_epf' => 0,
            'etf' => 0,
            'account_name' => $employee->account_name,
            'account_number' => $employee->account_number,
            'bank_name' => $employee->bank_name,
            'branch' => $employee->branch,
        ]);
        dd($payslip);
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

    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $id           = $request->id;
            $incentive  = $request->incentive;
            $late_deduction  = $request->late_deduction;

            $update = [

                'id'           => $id,
                'incentive' => $incentive,
                'late_deduction' => $late_deduction,
            ];
            pay_slip::where('id', $request->id)->update($update);
            DB::commit();
            Toastr::success('Payslip updated successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('payslip update fail :)', 'Error');
            return redirect()->back();
        }
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