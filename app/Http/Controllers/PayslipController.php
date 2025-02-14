<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\HalfDay;
use App\Models\Payslip;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\JobStatus;
use App\Models\Attendance;
use App\Models\department;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AttendanceReport;
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
            "incentive1" => 'required',
            "incentive2" => 'required',
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
        $job_title = $payslip->employee->j_title;
        $job_title_name = JobTitle::where('id', $job_title)->pluck('title_name')->first();

        $currentDate = Carbon::now()->format('F j, Y');
        $pdf = Pdf::loadView('payslip_pdf', compact('payslip', 'currentDate', 'job_title_name'))->setPaper('a4', 'portrait');
        $fileName = strtoupper(preg_split('#\s+#', $payslip->employee->full_name)[0]) . '.pdf';

        return $pdf->download($fileName);
    }

    public function jobStatus()
    {
        return $this->belongsTo(JobStatus::class, 'j_status');
    }


    public function create_payslip(AttendanceReport $attendanceReport)
    {
        $attandance_data = $attendanceReport->attributesToArray();
        $employee = $attendanceReport->employee;

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

        $incentive1 = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'like', 'Incentive 1')
            ->where('type', 'increments')
            ->sum('increment_amount');

        $incentive2 = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'like', 'Incentive 2')
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
            ->where('increment_name', 'Advanced')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $loan = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Loan')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $Hostal = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Hostal')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $Bodim = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Bodim')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $other_deductions = SalaryDetail::where('employee_id', $employee->employee_id)
            ->where('active', true)
            ->where('increment_name', 'Other')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $job_status = strtoupper(JobStatus::where('id', $employee->j_status)->value('status_name'));
        // dd($job_status);
        if ($job_status == "INTERN") {
            $basic_salary = $employee->basic_Salary;
            $br_allowance = 0;
        } else {
            $basic_salary = $employee->basic_Salary - 3500;
            // Increments
            $br_allowance = 3500;
        }
        $Edepartment = $employee->department->department;
        $gross_salary = $basic_salary + $br_allowance;
        // dd($gross_salary);
        $gross_salary_day = $gross_salary / 30;
        // dd($gross_salary_day);
        if ($Edepartment == "Local") {
            $gross_salary_hour = $gross_salary_day / 9;
        } else {
            $gross_salary_hour = $gross_salary_day / 10;
        }

        // Holiday payment
        $holiday_payment = 0;

        // Extra days payment
        // $extra_days = ($attandance_data['days_worked_weekend'] - $attandance_data['days_worked_holiday_weekend']);
        // $extra_days_payment = $extra_days * $gross_salary_day;

        // Overtime
        $ot_hours = $attandance_data['ot_minutes'] / 60;
        $ot_hours += ($attandance_data['days_worked_holiday']*10);
        // dd( $attandance_data['annual_leaves_taken']);

        $ot_rate = ( $gross_salary / 240 ) * 1.5;
        $ot = $ot_rate * $ot_hours;
        // dd($ot);

        if ($attandance_data['half_day'] != null) {
            $half_day = $attandance_data['half_day'] / 2;
        } else {
            $half_day = 0;
        }
        $this->updateHalfDay($employee->id, $attandance_data['half_day']);

        if ($attandance_data['annual_leaves_taken'] != null) {
            $annual_leaves_taken = $attandance_data['annual_leaves_taken'];
        } else {
            $annual_leaves_taken = 0;
        }
        $workdays = $attandance_data['work_days'];
        $days_worked = $attandance_data['days_worked'];
        // dd($workdays);
        // dd($days_worked);
        $attendance_date = abs($attandance_data['absent_days']);
        // dd($attendance_date);
        $leave = $annual_leaves_taken + $half_day;
        // No pay leave deduction
        $no_pay_leave_deduction =  $gross_salary_day * ($attendance_date - $leave);
        // dd( $attandance_data['absent_days'] );
        $late_hours = $attandance_data['late_minutes'] / 60;
        if ($late_hours <= 3) {
            $late_hours = 0;
        } else {
            $late_hours = $late_hours - 3;
        }
        $late_deduction = $gross_salary_hour * $late_hours;

        // Total basic pay
        $total_basic_pay = $gross_salary  - $no_pay_leave_deduction - $late_deduction;

        if ($job_status == "INTERN") {
            $employee_epf = 0;
            $company_epf = 0;
            $etf = 0;
        } else {
            // Employee EPF
            $employee_epf = ($total_basic_pay / 100) * 8;

            // Company EPF/ETF
            $company_epf = ($total_basic_pay / 100) * 12;
            $etf = ($total_basic_pay / 100) * 3;
        }
        // dd($attandance_data['absent_days']);
        $incentivesF1 = ($incentive1 / 30) * (30 - ($attandance_data['absent_days'] - ($half_day + $attandance_data['annual_leaves_taken'])));
        $incentivesF2 = ($incentive2 / 30) * (30 - ($attandance_data['absent_days'] - ($half_day + $attandance_data['annual_leaves_taken'])));
        // dd($incentivesF);
        $payslip = new Payslip();

        $taxSend = $incentive1 + $incentive2 + $gross_salary;
        $taxAmount = $payslip->calculateTax($taxSend);

        // $increments = $holiday_payment  + $incentivesF + $ot + $other_incrmeents ;
        $increments = $total_basic_pay + $ot + $holiday_payment + $incentivesF1 + $incentivesF2 + $other_incrmeents;
        $deductions = $employee_epf + $taxAmount + $advance + $other_deductions + $Hostal + $Bodim;
        // dd($total_basic_pay);
        $netSalary =  $increments - $deductions;
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
            'other_deductions' => $other_deductions + $Hostal + $Bodim,

            'holiday_payment' => $holiday_payment,
            'extra_days_payment' => 0,
            'incentive1' => $incentivesF1,
            'incentive2' => $incentivesF2,
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
    public function updateHalfDay($employee_id, $half_day)
    {
        $halfDay = HalfDay::where('employee_id', $employee_id)->first();

        if ($halfDay) {
            $currentDate = Carbon::now()->format('Y-m');
            $updatedDate = Carbon::parse($halfDay->updated_at)->format('Y-m');
            // dd($currentDate);
            if ($updatedDate != $currentDate) {
                $currentHalfDayCount = $halfDay->half_day_count;
                $halfDay->half_day_count = ($currentHalfDayCount - $half_day) + 1;
                $halfDay->save();
            }
        }
    }

    // Generate payslips for current month
    public function generate_payslips()
    {
        // $startOfMonth = Carbon::now()->subMonths(2)->startOfMonth(); // December 1, 2024
        // $endOfMonth = Carbon::now()->subMonths(2)->endOfMonth(); // December 31, 2024
        $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfMonth = Carbon::now()->subMonth()->endOfMonth();

        // TO Do uncomment
        // $attendanceReports = AttendanceReport::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        // TO DO Comment
        $attendanceReports = AttendanceReport::orderBy('created_at', 'desc')->get();
        
        $attendanceReports->each(function ($attendanceReport) {
            $this->create_payslip($attendanceReport);
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

    public function get_salary_report(Request $request)
    {

        try {
            $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfMonth = Carbon::now()->subMonth()->endOfMonth();
            $payslips = Payslip::whereBetween('date', [$startOfMonth, $endOfMonth]);

            if ($request->filled('department')) {
                $payslips->whereHas('employee', function ($query) use ($request) {
                    $query->where('d_name', $request->department);
                });
            }

            if ($request->filled('year')) {
                $payslips->whereYear('date', $request->year);
            }

            if ($request->filled('month')) {
                $payslips->whereMonth('date', $request->month);
            }

            $payslips = $payslips->get();

            $departments = Department::select('id', 'department')->distinct()->get();

            return view('reports/salary-report', compact('departments', 'payslips'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
    public function search(Request $request)
    {
        $pay = Payslip::all();
        $departments = Department::all();
        $year = $request->input('year'); // Assuming the year is sent in the request
        $month = $request->input('month'); // Assuming the month is sent in the request
        $department = $request->input('department');

        if ($year != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ?', [$year])->get();
        }
        if ($month != null) {
            $payslips = Payslip::whereRaw('MONTH(date) = ?', [$month])->get();
        }
        if ($department != null) {
            $payslips = $pay->filter(function ($payslip) use ($department) {
                return $payslip->employee->d_name == $department;
            });
        }
        if ($year != null && $month != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])->get();
        }
        if ($year != null && $department != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ?', [$year])
                ->get()
                ->filter(function ($payslip) use ($department) {
                    return $payslip->employee->d_name == $department;
                });
        }
        if ($month != null && $department != null) {
            $payslips = Payslip::whereRaw('MONTH(date) = ?', [$month])
                ->get()
                ->filter(function ($payslip) use ($department) {
                    return $payslip->employee->d_name == $department;
                });
        }
        if ($year != null && $month != null && $department != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])->get()
                ->filter(function ($payslip) use ($department) {
                    return $payslip->employee->d_name == $department;
                });
        }
        if ($year == null && $month == null) {
            $payslips = Payslip::all();
        }


        return view('reports/salary-report', compact('departments', 'payslips'));
    }

    public function searchPayslip(Request $request)
    {
        // Your logic to search payslips here
        // dd($request);
        $year = $request->input('year'); // Assuming the year is sent in the request
        $month = $request->input('month'); // Assuming the month is sent in the request
        if ($year != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ?', [$year])->get();
        }
        if ($month != null) {
            $payslips = Payslip::whereRaw('MONTH(date) = ?', [$month])->get();
        }
        if ($year != null && $month != null) {
            $payslips = Payslip::whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])->get();
        }
        if ($year == null && $month == null) {
            $payslips = Payslip::all();
        }
        if ($year == null && $month == null) {
            $date = now()->startOfMonth()->subMonth();
            $payslips = Payslip::whereDate('date', $date)->get();
        }

        return view('reports/payslip-approve', compact(['payslips']));
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

        $hostal = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Hostal')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $Others = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Others')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        $deduction_others = $bodim + $Others+$hostal;

        $Advanced = SalaryDetail::where('employee_id', $employeeId)
            ->where('increment_name', 'Advanced')
            ->where('type', 'deductions')
            ->sum('increment_amount');

        return response()->json([
            'basic_salary' => $basicSalary,
            'brAllowance' => $brAllowance,
            'incentive1' => $incentive1,
            'incentive2' => $incentive2,
            'increment_others' => $increment_others,
            'deduction_others' => $deduction_others,
            'Advanced' => $Advanced
        ]);
    }
}
