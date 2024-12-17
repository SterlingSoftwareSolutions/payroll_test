<?php

namespace App\Http\Controllers;

use App\Models\AnnualLeaves;
use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\department;
use App\Models\Employee;
use App\Models\HalfDay;
use App\Models\Holiday;
use App\Models\JobStatus;
use App\Models\JobTitle;
use App\Models\Note;
use Brian2694\Toastr\Toastr;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;
use view;

class AttendanceReportController extends Controller
{
    /**
     * Display a listing of the attendance report.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $startOfMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfMonth = Carbon::now()->subMonth()->endOfMonth();
            $attendanceReports = AttendanceReport::whereBetween('date', [$startOfMonth, $endOfMonth]);

            if ($request->filled('department')) {
                $attendanceReports->whereHas('employee', function ($query) use ($request) {
                    $query->where('d_name', $request->department);
                });
            }

            if ($request->filled('year')) {
                $attendanceReports->whereYear('date', $request->year);
            }

            if ($request->filled('month')) {
                $attendanceReports->whereMonth('date', $request->month);
            }

            $attendanceReports = $attendanceReports->get();

            $departments = Department::select('id', 'department')->distinct()->get();

            return view('reports.attendance-report', compact('departments', 'attendanceReports'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }





    public function generate_reports(Request $request)
    {

        // dd($request);
        if ($request->department_id) {
            $employees = Employee::where('status', 'active')->where('d_name', $request->department_id);
        } else {
            $employees = Employee::where('status', 'active');
        }

       
        $employees->each(function ($employee) use ($request) {
            $attendanceData = $employee->attendance_data($request->year ?? null, $request->month ?? null);
            // dd($employee->id);
            // dd($attendanceData["ot_minutes"],);
            $attendanceData['current'] = Carbon::parse($attendanceData['current'])->format('Y-m-d');
            $atten = abs($attendanceData["no_pay_leaves"]);
            $attendanceReport = AttendanceReport::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => $attendanceData['current']
                ],
                [
                    "month_days" => $attendanceData["month_days_count"],
                    "month_weekends" => $attendanceData["month_weekends_count"],
                    "month_holidays" => $attendanceData["month_holidays"]->count(),
                    "work_days" => $attendanceData["work_days"],
                    "work_hours" => $attendanceData["work_hours"],
                    "days_worked" => $attendanceData["days_worked"],
                    "days_worked_holiday" => $attendanceData["days_worked_holiday"],
                    "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
                    "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
                    "late_minutes" => $attendanceData["late_minutes"],
                    "ot_minutes" => $attendanceData["ot_minutes"],
                    "annual_leaves_taken" => 0,
                    "annual_leaves" => $attendanceData["annualLeaves"] ?? 0,
                    "absent_days" => $atten,
                    "work_half_day" => $attendanceData["work_half_day"],
                    "remove_late_minutes" => $attendanceData["remove_late_minutes"],
                ]
            );
            // dd($attendanceReport);
        });

        return redirect()->route('form.attendance.index');
    }

    public function edit(AttendanceReport $attendanceReport)
    {
        // dd($attendanceReport);
        $notes = Note::where('report_id', $attendanceReport->id)->get();
        // dd($notes);
        $employee_id = $attendanceReport->employee_id;
        $halfDayCount = HalfDay::where('employee_id', $employee_id)->value('half_day_count');
        // dd($halfDayCount);
        return view("reports/attendance-report-edit", compact('attendanceReport', 'halfDayCount', 'notes'));
    }

    public function update(AttendanceReport $attendanceReport, Request $request)
    {
        $note = $request->note;
        $attendanceId = $request->attendance_id;
        // dd($request);
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
            'half_day' => 'nullable'
        ]);
        // dd($validatedData);
        if ($note != "") {
            $this->noteupdate($note, $attendanceId);
        }
        $attendanceReport->update($validatedData);

        return redirect()->route('form.attendance.edit', ['attendanceReport' => $attendanceReport]);
    }

    public function noteupdate($note, $attendanceId)
    {
        $user = Auth::user();
        Note::create([
            'report_id' => $attendanceId,
            'user_id' => $user->id,
            'note' => $note,
        ]);
    }

    public function calculateAnnualLeave($employeeId)
    {
        // dd($employeeId);

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
