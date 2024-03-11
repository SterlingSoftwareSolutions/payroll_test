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

        $departments = Department::all();


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


    // public function store(Request $request)
    // {
    //     dd($request)
    //     // Validate the request data
    //     $validator = Validator::make($request->all(), [
    //         'employee_id' => 'required|exists:employees,id',
    //         'date' => 'required|date',
    //         'month_days_count' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $attendanceReportData = [
    //         'employee_id' => $request->input('employee_id'),
    //         'date' => $request->input('date'),
    //         'month_days_count' => $request->input('month_days_count'),
    //     ];

    //     dd($attendanceReportData);

    //     $attendanceReport = AttendanceReport::updateOrCreate(
    //         ['employee_id' => $attendanceReportData['employee_id'], 'date' => $attendanceReportData['date']],
    //         $attendanceReportData
    //     );

    //     dd($attendanceReport);

    //     return redirect()->route('reports.attendance-report')->with('success', 'Attendance report saved successfully');
    // }






    // public function edit(AttendanceReport $attendanceReport)
    // {
    //     $attendanceReport = AttendanceReport::findOrFail($attendanceReport->id);
    //     $employees = Employee::all();
    //     $departments = Department::all();
    //     $holiday = Holiday::all();  

    //     // Add any additional data you need for the edit form

    //     // Pass data to the view
    //     return view('reports.edit.attendancereportedit', compact('attendanceReport', 'employees', 'departments', 'holiday'));
    // }
    public function edit($employeeId)
    {
        $attendanceReport = AttendanceReport::where('employee_id', $employeeId)->first();


        return view('reports.edit.attendancereportedit', compact('attendanceReport'));
    }









    public function update(Request $request, $id)
    {
        $request->validate([]);

        $attendanceReport = AttendanceReport::findOrFail($id);

        $attendanceReport->update([]);

        return redirect()->route('reports.attendance-report')->with('success', 'Attendance report updated successfully');
    }


    public function generateAttendanceReport(Request $request, $employeeId)
    {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $year = $request->input('year', null);
        $month = $request->input('month', null);

        $attendanceData = $employee->attendance_data($year, $month);
    
        $attendanceReport = AttendanceReport::updateOrCreate([
            'employee_id' => $employeeId,
            'date' => today()->firstOfMonth()
        ],[
            "month_days" => $attendanceData["month_days_count"],
            "month_weekends" => $attendanceData["month_weekends_count"],
            "month_holidays" => $attendanceData["month_holidays"]->count(),
            "work_days" => $attendanceData["work_days"],
            "work_hours" => $attendanceData["work_hours"],
            "absent_days" => $attendanceData["no_pay_leaves"],
            "days_worked" => $attendanceData["days_worked"]->count(),
            "days_worked_holiday" => $attendanceData["days_worked_holiday"]->count(),
            "days_worked_weekend" => $attendanceData["days_worked_weekend"]->count(),
            "days_worked_holiday_weekend" => $attendanceData["days_worked_holiday_weekend"]->count(),
            "late_minutes" => $attendanceData["late_minutes"],
            "ot_minutes" => $attendanceData["ot_minutes"],
            "annual_leaves_taken" => 0,
        ]);
//dd($attendanceReport);
        return view('reports.attendance-report', ['attendanceData' => $attendanceData]);
    }
}
