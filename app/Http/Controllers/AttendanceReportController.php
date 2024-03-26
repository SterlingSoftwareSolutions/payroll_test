<?php

namespace App\Http\Controllers;

use Log;
use view;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\JobStatus;
use App\Models\Attendance;
use App\Models\department;
use App\Models\AnnualLeaves;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Models\AttendanceReport;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AttendanceReportController extends Controller
{
    /**
     * Display a listing of the attendance report.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)    //attendance report page view
    {
        $date = Carbon::create($request->year ?? now()->subMonth()->year, $request->month ?? now()->subMonth()->month);
        $attendanceReports = AttendanceReport::whereDate('date', $date )->get();
        if($request->department){
            $dep = $request->department;
            $attendanceReports = $attendanceReports->filter(function ($attendanceReport) use ($dep){
                return $attendanceReport->employee->d_name == $dep;
            });
        }

        $departments = department::select('id', 'department')->distinct()->get();
        return view('reports.attendance-report', compact([
            'departments',
            'attendanceReports'
        ]));
    }




    public function generate_reports(Request $request){
        if($request->department_id){
            $employees = Employee::where('status', 'active')->where('d_name', $request->department_id);
        } else{
            $employees = Employee::where('status', 'active');
        }

        $employees->each(function ($employee) use ($request){
            $attendanceData = $employee->attendance_data($request->year ?? null, $request->month ?? null);
            AttendanceReport::firstOrCreate([
                'employee_id' => $employee->id,
                'date' => $attendanceData['current']
            ], [
                "month_days" => $attendanceData["month_days_count"],
                "month_weekends" => $attendanceData["month_weekends_count"],
                "month_holidays" => $attendanceData["month_holidays"]->count(),
                "work_days" => $attendanceData["work_days"],
                "work_hours" => $attendanceData["work_hours"],
                "days_worked" => $attendanceData["days_worked"]->count(),
                "days_worked_holiday" => $attendanceData["days_worked_holiday"]->count(),
                "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
                "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
                "late_minutes" => $attendanceData["late_minutes"],
                "ot_minutes" => $attendanceData["ot_minutes"],
                "annual_leaves_taken" => 0,
                "annual_leaves" => $attendanceData["annualLeaves"] ?? 0,
                "absent_days" => $attendanceData["no_pay_leaves"],
            ]);
        });

        return redirect()->route('form.attendance.index');
    }

    public function edit(AttendanceReport $attendanceReport){
        return view("reports/attendance-report-edit", compact('attendanceReport'));
    }

    public function update(AttendanceReport $attendanceReport, Request $request){
        $validatedData = $request->validate([
            'employee_id' => 'required|integer',
            'date' => 'required|date',
            'month_days' => 'required',
            'month_weekends' => 'required',
            'month_holidays' => 'required',
            'work_days' => 'required',
            'work_hours' => 'required',
            'absent_days' => 'required',
            'days_worked' => 'required',
            'days_worked_holiday' => 'required',
            'days_worked_weekend' => 'required',
            // 'days_worked_holiday_weekend' => 'required',
            'late_minutes' => 'required',
            'ot_minutes' => 'required',
            'annual_leaves' => 'required',
            'annual_leaves_taken' => 'required',
        ]);
        $attendanceReport->update($validatedData);
        return redirect()->route('form.attendance.edit', ['attendanceReport' => $attendanceReport]);
    }

    public function calculateAnnualLeave($employeeId)
    {
        dd($employeeId);

        $employee = Employee::find($employeeId);

        if (!$employee) {

            return 0;
        }

        $joinedDate = Carbon::parse($employeeId);

        $januaryFirst = Carbon::parse('January 1');
        $aprilFirst = Carbon::parse('April 1');
        $julyFirst = Carbon::parse('July 1');
        $octoberFirst = Carbon::parse('October 1');

        if ($joinedDate->gte($januaryFirst) && $joinedDate->lt($aprilFirst)) {
            $annualLeave = 14;
        } elseif ($joinedDate->gte($aprilFirst) && $joinedDate->lt($julyFirst)) {
            $annualLeave = 10;
        } elseif ($joinedDate->gte($julyFirst) && $joinedDate->lt($octoberFirst)) {
            $annualLeave = 7;
        } elseif ($joinedDate->gte($octoberFirst) && $joinedDate->lte(Carbon::parse('December 31'))) {
            $annualLeave = 4;
        } else {
            $annualLeave = 0;
        }

        $maxLeave = 21;
        $annualLeave = min($annualLeave, $maxLeave);

        $joinedDate = '2023-03-15'; // Replace actual joint date
        $annualLeave = $this->calculateAnnualLeave($joinedDate);


        // $joinedDate = $employee->joinedDate; 
        // $annualLeave = $this->calculateAnnualLeave($joinedDate);

        return view('reports.edit.attendancereportedit', [
            'employee' => $employee, 'annualLeave' => $annualLeave,
        ]);
    }








}
