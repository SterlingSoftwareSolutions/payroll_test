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





    private function getDaysInMonth($month, $year)
    {
        if ($month == "02") {
            return ($year % 4 == 0) ? 29 : 28;
        } elseif (in_array($month, ["01", "03", "05", "07", "08", "10", "12"])) {
            return 31;
        } else {
            return 30;
        }
    }


    private function getWeekendCount($month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($startDate, $interval, $endDate);

        $weekendCount = 0;

        foreach ($period as $date) {
            if ($date->format('N') >= 6) {
                $weekendCount++;
            }
        }
        return $weekendCount;
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

    // public function generateAttendanceReport(Request $request, $employeeId)
    // {
    //     $departments = department::all();

    //     $employee = Employee::find($employeeId);

    //     if (!$employee) {
    //         return response()->json(['error' => 'Employee not found'], 404);
    //     }

    //     $year = $request->input('year', null);
    //     $month = $request->input('month', null);
    //     $attendanceData = $employee->attendance_data($year, $month);

    //     $attendanceReport = AttendanceReport::updateOrCreate([
    //         'employee_id' => $employeeId,
    //         'date' => today()->firstOfMonth()
    //     ], [
    //         "month_days" => $attendanceData["month_days_count"],
    //         "month_weekends" => $attendanceData["month_weekends_count"],
    //         "month_holidays" => $attendanceData["month_holidays"]->count(),
    //         "work_days" => $attendanceData["work_days"],
    //         "work_hours" => $attendanceData["work_hours"],
    //         "days_worked" => $attendanceData["days_worked"]->count(),
    //         "days_worked_holiday" => $attendanceData["days_worked_holiday"]->count(),
    //         "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
    //         "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
    //         "late_minutes" => $attendanceData["late_minutes"],
    //         "ot_minutes" => $attendanceData["ot_minutes"],
    //         "annual_leaves_taken" => 0,
    //         "annual_leaves" => $attendanceData["annualLeaves"] ?? 0,
    //         "absent_days" => $attendanceData["no_pay_leaves"],
    //     ]);

    //     return view('reports.edit.attendancereportedit', ['attendanceReport' => $attendanceReport, 'attendanceData' => $attendanceData, 'departments' => $departments]);
    // }


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




    // public function annual_leaves($year = null)
    // {
    //     if(!$year){
    //         $year = now()->year;
    //     }

    //     $annual_leaves = AnnualLeaves::where('employee_id', $this->id)->where('year', $year)->first();
    //     if(!$annual_leaves){
    //         AnnualLeaves::create([
    //             'employee_id' => $this->id,
    //             'year' => $year,
    //             'total_leaves' => $this->calculate_annual_leaves($year)
    //         ]);


    //     }






}
