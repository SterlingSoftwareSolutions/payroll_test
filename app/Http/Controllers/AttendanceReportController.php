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
use App\Models\Attendance;
use App\Models\department;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Models\AttendanceReport;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceReportController extends Controller
{
    /**
     * Display a listing of the attendance report.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $query = Employee::query();
        $current_month = now()->month;
        $current_year = now()->year;

        $employees = $query->get();

        $departments = department::all();
       //dd($departments);


        $holiday = Holiday::all();
        $attendances = Attendance::with('employee', 'holiday')
            ->whereMonth('date', $current_month)
            ->whereYear('date', $current_year)
            ->get();


        $employeeHolidayCounts = [];                //for holidays count

        $attendances->each(function ($attendance) use ($holiday, &$employeeHolidayCounts) {
            $attendanceDate = date('d-m-Y', strtotime($attendance->date));

            $attendance->is_holiday = $holiday->contains('date_holiday', $attendanceDate);

            $employeeId = $attendance->employee_id;
            $employeeHolidayCounts[$employeeId] = ($employeeHolidayCounts[$employeeId] ?? 0) + ($attendance->is_holiday ? 1 : 0);

            $punchIn = new DateTime($attendance->punch_in);
            $punchOut = new DateTime($attendance->punch_out);
            $workHours = $punchOut->diff($punchIn)->format('%H:%I');

            $regularWorkingHours = new DateTime('10:00');
            $workHours = new DateTime($punchOut->diff($punchIn)->format('%H:%I'));
            $overtime = $workHours > $regularWorkingHours ? $workHours->diff($regularWorkingHours)->format('%H:%I') : '00:00';

            $attendance->overtime = $overtime;
        });

        $attendanceCounts = DB::table('attendances')                    //attendance count
            ->select('employee_id', DB::raw('count(*) as attendance_count'))
            ->groupBy('employee_id')
            ->get();

        $totDays = $this->getDaysInMonth($current_month, $current_year);
        $weekendCount = $this->getWeekendCount($current_month, $current_year);

        $extraDaysCount = $attendances->filter(function ($attendance) {
            $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
            return $dayOfWeek == 6 || $dayOfWeek == 0;  // Note Saturday (6) or Sunday (0)
        })->count();



        $departments = department::select('id', 'department')->distinct()->get();




        return view('reports.attendance-report', compact([
            'departments', 'employees', 'attendances',
            'attendanceCounts', 'holiday', 'current_month', 'current_year', 'totDays',
            'weekendCount', 'extraDaysCount', 'employeeHolidayCounts'
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


    // public function edit($employeeId)
    // {
    //     $attendanceReport = AttendanceReport::where('employee_id', $employeeId)->first();


    //     return view('reports.edit.attendancereportedit', compact('attendanceReport'));
    // }





    public function editAttendanceReport($employeeId)
{
    $departments = department::all();

    $employee = Employee::find($employeeId);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    $attendanceReport = AttendanceReport::where('employee_id', $employeeId)
        ->where('date', today()->firstOfMonth())
        ->first();
    // dd($attendanceReport);
    if (!$attendanceReport) {
        return redirect()->route('form.attendance.index')->with('error', 'Attendance report not found for the selected employee.');
    }



    return view('reports.edit.attendancereportedit', ['employee' => $employee, 'attendanceReport' => $attendanceReport]);
}




    public function updateAttendanceReport(Request $request, $employeeId)
    {

       
        $attendanceData = AttendanceReport::find($employeeId);

        $year = $request->input('year', null);
        $month = $request->input('month', null);

        $attendanceData->update([

            'date' => $request->input('date'),
            // 'month_days' => $request->input('month_days'),
            'employee_id' => $employeeId,

            "month_holidays" => is_array($attendanceData["month_holidays"]) ? count($attendanceData["month_holidays"]) : 0,
            "days_worked" => is_array($attendanceData["days_worked"]) ? count($attendanceData["days_worked"]) : 0,
            "days_worked_holiday" => is_array($attendanceData["days_worked_holiday"]) ? count($attendanceData["days_worked_holiday"]) : 0,
            "days_worked_weekend" => is_array($attendanceData["days_worked_weekend"]) ? count($attendanceData["days_worked_weekend"]) : 0,
            "days_worked_holiday_weekend" => is_array($attendanceData["days_worked_holiday_weekend"]) ? count($attendanceData["days_worked_holiday_weekend"]) : 0,


            "month_days" => $attendanceData["month_days"],
            "month_weekends" => $attendanceData["month_weekends"],
            // "month_holidays" => $attendanceData["month_holidays"]->count(),
            "work_days" => $attendanceData["work_days"],
            "work_hours" => $attendanceData["work_hours"],
            "absent_days" => $attendanceData["absent_days"],
            // "days_worked" => $attendanceData["days_worked"]->count(),
            // "days_worked_holiday" => $attendanceData["days_worked_holiday"]->count(),
            // "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
            // "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
            "late_minutes" => $attendanceData["late_minutes"],
            "ot_minutes" => $attendanceData["ot_minutes"],

        ]);
        // dd($attendanceData);
       

        return view('reports.attendance-report', ['attendanceReport' => $attendanceData]);
    }

    public function generateAttendanceReport(Request $request, $employeeId)
    {
    //    dd($request);
        $employee = Employee::find($employeeId);

        if (!$employee) {

            return response()->json(['error' => 'Employee not found'], 404);
        }

        $year = $request->input('year', null);
        $month = $request->input('month', null);


        // $joinedDate = $employee->joinedDate; 
        // // dd($joinedDate);
        // $annualLeave = $this->calculateAnnualLeave($joinedDate);
        // // dd($joinedDate);


        $attendanceData = $employee->attendance_data($year, $month);
        // dd($attendanceData);
        $attendanceReport = AttendanceReport::updateOrCreate([
            'employee_id' => $employeeId,
            'date' => today()->firstOfMonth()
        ], [
            "month_days" => $attendanceData["month_days_count"],
            "month_weekends" => $attendanceData["month_weekends_count"],
            "month_holidays" => $attendanceData["month_holidays"]->count(),
            "work_days" => $attendanceData["work_days"],
            "work_hours" => $attendanceData["work_hours"],
            // "absent_days" => $attendanceData["absent_days"],
            "absent_days" => $attendanceData["absent_days"] ?? 0,
            "days_worked" => $attendanceData["days_worked"]->count(),
            "days_worked_holiday" => $attendanceData["days_worked_holiday"]->count(),
            "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
            "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
            "late_minutes" => $attendanceData["late_minutes"],
            "ot_minutes" => $attendanceData["ot_minutes"],
            "annual_leaves_taken" => 0,
            // "annual_leaves" => $annualLeave,
           
        ]);
        // dd($attendanceReport);
        // dd($attendanceData);

        return view('reports.edit.attendancereportedit', ['attendanceReport' => $attendanceReport, 'attendanceData' => $attendanceData]);
    }






    public function attendanceReportSearch(Request $request)
    {
        $current_month = now()->month;
        $current_year = now()->year;
        $holiday = Holiday::all();
        $attendances = Attendance::with('employee', 'holiday')
            ->whereMonth('date', $current_month)
            ->whereYear('date', $current_year)
            ->get();

        $totDays = $this->getDaysInMonth($current_month, $current_year);
        $departments = department::all();
        $attendanceCounts = DB::table('attendances')
            ->select('employee_id', DB::raw('count(*) as attendance_count'))
            ->groupBy('employee_id')
            ->get();
        $weekendCount = $this->getWeekendCount($current_month, $current_year);
        $extraDaysCount = $attendances->filter(function ($attendance) {
            $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
            return $dayOfWeek == 6 || $dayOfWeek == 0;  // Note Saturday (6) or Sunday (0)
        })->count();
        $employeeHolidayCounts = [];

        $attendances->each(function ($attendance) use ($holiday, &$employeeHolidayCounts) {
            $attendanceDate = date('d-m-Y', strtotime($attendance->date));
            $attendance->is_holiday = $holiday->contains('date_holiday', $attendanceDate);
            $employeeId = $attendance->employee_id;
            $employeeHolidayCounts[$employeeId] = ($employeeHolidayCounts[$employeeId] ?? 0) + ($attendance->is_holiday ? 1 : 0);
        });

        $current_year = now()->year; // assuming $current_year is defined elsewhere

        if ($request->has('month')) {
            $month = $request->month;
            $attendances = Attendance::whereMonth('date', '=', $month)->get();
        }

        if ($request->has('year')) {
            $year = $request->year;
            if ($year == null) {
                $year = $current_year;
            } elseif ($year != null && strlen($year) === 4 && is_numeric($year)) {
                $attendances = Attendance::whereYear('date', '=', $year)->get();
            } else {
                return response()->json(['error' => 'Invalid year format'], 400);
            }
        }

        if ($request->has('department')) {
            $departmentName = $request->input('department');
            $employeeIds = Employee::where('d_name', $departmentName)->pluck('id');
            $attendances = Attendance::whereIn('employee_id', $employeeIds)->get();
        }

        if ($request->has('month') && $request->has('year')) {
            $month = $request->month;
            $year = $request->year;
            if ($year == null) {
                $year = $current_year;
            } elseif ($year != null && strlen($year) === 4 && is_numeric($year)) {
                $attendances = Attendance::whereMonth('date', '=', $month)
                    ->whereYear('date', '=', $year)
                    ->get();
            } else {
                return response()->json(['error' => 'Invalid year format'], 400);
            }
        }

        if ($request->has('department') && $request->has('month')) {
            $departmentName = $request->input('department');
            $employeeIds = Employee::where('d_name', $departmentName)->pluck('id');
            $month = $request->month;
            $attendances = Attendance::whereIn('employee_id', $employeeIds)
                ->whereMonth('date', '=', $month)
                ->get();
        }

        if ($request->has('department') && $request->has('month') && $request->has('year')) {
            $departmentName = $request->input('department');
            $employeeIds = Employee::where('d_name', $departmentName)->pluck('id');
            $month = $request->month;
            $year = $request->year;
            if ($year == null) {
                $year = $current_year;
            } elseif ($year != null && strlen($year) === 4 && is_numeric($year)) {
                $attendances = Attendance::whereIn('employee_id', $employeeIds)
                    ->whereMonth('date', '=', $month)
                    ->whereYear('date', '=', $year)
                    ->get();
            } else {
                return response()->json(['error' => 'Invalid year format'], 400);
            }
        }

        $employees = Employee::all();

        return view('reports.attendance-report', compact('holiday', 'employeeHolidayCounts', 'employees', 'attendances', 'departments', 'totDays', 'attendanceCounts', 'weekendCount', 'extraDaysCount'));
    }





    public function calculateAnnualLeave($employeeId)
    {

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

        // return view('reports.edit.attendancereportedit', [
        //      'employee' => $employee,
            
        //     'annualLeave' => $annualLeave,
        //     ]);
    }
}
