<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payslip;
use App\Models\Employee;
use App\Models\JobStatus;
use App\Models\Attendance;
use App\Models\department;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;

class PayslipController extends Controller
{
    public function index()
    {
        $date = now()->startOfMonth()->subMonth();
        $payslips = Payslip::whereDate('date', $date)->get();
        // dd($payslips);
        return view('reports/payslip-approve', compact(['payslips']));
    }

    public function show(Payslip $payslip)
    {
        // $payslipdata = $payslip->attributesToArray();
        // $payslipdata['employee_employee_id'] = $payslip->employee->employee_id;
        // $payslipdata['net_salary'] = $payslip->net_salary();
        $payslipdata = Payslip::find($payslip);
        $payslipdata['employee_employee_id'] = $payslip->employee->employee_id;
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
        $pdf = Pdf::loadView('payslip_pdf', compact('payslip'))->setPaper('a4', 'portrait');
        $fileName = strtoupper(preg_split('#\s+#', $payslip->employee->full_name)[0]) . '.pdf';
        return $pdf->download($fileName);
    }

    public function create_payslip(Employee $employee)
    {
        $attandance_data = $employee->attendance_data();

        $job_status = strtoupper(JobStatus::where('id', $employee->j_status)->value('status_name'));
        if ($job_status == "INTERN ") {
            $basic_salary = $employee->basic_Salary;
            $br_allowance = 0;
        } else {
            $basic_salary = $employee->basic_Salary - 3500;
            // Increments
            $br_allowance = 3500;
        }

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


        $startDate = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endDate = now()->subMonth()->endOfMonth()->format('Y-m-d');

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $sumOTSeconds = 0;

        foreach ($attendances as $attendance) {
            // Split the OT time into hours, minutes, and seconds
            list($hours, $minutes, $seconds) = explode(':', $attendance->OT);

            // Convert OT time to total seconds and add to sum
            $sumOTSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
        }
        $sumOTHours = gmdate("H:i:s", $sumOTSeconds);
        $sumLateSeconds = 0;
        foreach ($attendances as $attendance) {
            // Split the OT time into hours, minutes, and seconds
            list($hours, $minutes, $seconds) = explode(':', $attendance->late);

            // Convert OT time to total seconds and add to sum
            $sumLateSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
        }
        $sumLateHours = gmdate("H:i:s", $sumLateSeconds);
        // dd($sumLateHours . "  " . $sumOTSeconds);
        // Salary amounts
        $gross_salary = $basic_salary + $br_allowance;
        // dd($gross_salary);
        $gross_salary_day = $gross_salary / 30;
        $gross_salary_hour = $gross_salary_day / 9;

        // Holiday payment
        $holiday_payment = $attandance_data['days_worked_holiday']->count() * $gross_salary_day;

        // Extra days payment
        $extra_days = ($attandance_data['days_worked_weekend']->count() - $attandance_data['days_worked_holiday_weekend']->count());
        $extra_days_payment = $extra_days * $gross_salary_day;

        // Overtime
        $ot_hours = $attandance_data['ot_minutes'] / 60;
        // dd($ot_hours);
        $ot_rate = $gross_salary / 240 * 1.5;
        $ot = $ot_rate * $ot_hours;

        list($otHours, $otMinutes, $otSeconds) = explode(':', $sumOTHours);
        $decimalOTHours = $otHours + ($otMinutes / 60) + ($otSeconds / 3600);

        // Calculate overtime pay
        $overtimePay = ($gross_salary / 240) * 1.5 * $decimalOTHours;

        // dd($ot);

        // list($otHours, $otMinutes, $otSeconds) = explode(':', $sumLateHours);
        // $decimalLateHours = $otHours + ($otMinutes / 60) + ($otSeconds / 3600);

        // // Calculate overtime pay
        // $Latetime = ($gross_salary / 240) * 1.5 * $decimalLateHours;

        // // dd($Latetime);

        // No pay leave deduction
        $no_pay_leave_deduction =  $gross_salary_day * $attandance_data['no_pay_leaves'];
        // // dd( $attandance_data['days_worked_holiday_weekend']->count());
        // // Late hours deduction
        // // dd($attandance_data['no_pay_leaves']);
        $late_hours = $attandance_data['late_minutes'] / 60;
        $late_deduction = $gross_salary_hour * $late_hours;

        // dd($late_deduction);
        // Total basic pay
        $total_basic_pay = $gross_salary  - $no_pay_leave_deduction - $late_deduction;

        // Employee EPF
        $employee_epf = ($total_basic_pay / 100) * 8;

        // Company EPF/ETF
        $company_epf = ($total_basic_pay / 100) * 12;
        $etf = ($total_basic_pay / 100) * 3;

        $incentivesF = ($incentives / 30) * (30 - $attandance_data['no_pay_leaves']);
        // dd($incentivesF);
        $payslip = new Payslip();

        $taxSend = $incentives + $gross_salary;
        $taxAmount = $payslip->calculateTax($taxSend);

        // $increments = $holiday_payment + $extra_days_payment + $incentivesF + $ot + $other_incrmeents ;
        $increments = $total_basic_pay + $ot + $holiday_payment + $incentivesF + $other_incrmeents;
        // dd($holiday_payment);
        // $deductions = $no_pay_leave_deduction + $late_deduction + $employee_epf + $advance + $other_deductions+$taxAmount;
        $deductions = $employee_epf + $taxAmount + $advance;

        $netSalary =  $increments - $deductions;
        // $netSalary = $netSalary - $taxAmount;
        dd($taxAmount);
        $payslip = Payslip::firstOrCreate([
            'employee_id' => $employee->id,
            'date' => now()->startOfMonth()->subMonth(),
        ], [
            'approved_at' => null,

            'basic_salary' => $basic_salary,
            'br_allowance' => $br_allowance,
            'fixed_allowance' => $fixed_allowance,
            'attendance_allowance' => $attendance_allowance,

            'no_pay_leave_deduction' => $no_pay_leave_deduction,
            'late_deduction' => $late_deduction,

            'employee_epf' => $employee_epf,
            'paye' => $taxAmount,
            'stamp_duty' => 0,

            'advance' => $advance,
            'loan' => $loan,
            'other_deductions' => $other_deductions,

            'holiday_payment' => $holiday_payment,
            'extra_days_payment' => $extra_days_payment,
            'incentives' => $incentives,
            'ot' => $ot,
            'other_increments' => $other_incrmeents,

            'company_epf' => $company_epf,
            'etf' => $etf,
            'net_salary' => $netSalary,

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
        $employees->each(function ($employee) {
            $this->create_payslip($employee);
        });
        return redirect('/form/payslip/approve');
    }

    // Approve all paylips for the current month
    public function approve_all()
    {
        $date = now()->startOfMonth()->subMonth();
        Payslip::whereDate('date', $date)->update([
            'approved_at' => now()
        ]);
        return redirect('/form/payslip/approve');
    }

    public function get_salary_report()
    {

        $query = Employee::query();
        $employees = $query->get();
        $payslips = Payslip::all();
        $departments = department::all();

        return view('reports/salary-report', compact('departments', 'employees', 'payslips'));
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