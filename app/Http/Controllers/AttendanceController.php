<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use DatePeriod;
use DateInterval;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        if ($request->department) {
            $filterDep = department::find($request->department);
            $query->where('d_name', $filterDep->department);
        }
        $employees = Employee::all();
        $departments  = department::all();

        $employees = $query->get();
        $holiday = Holiday::all();
        $attendances = Attendance::with('employee', 'holiday')->get();


        // $attendances->each(function ($attendance) use ($holiday) {
        //     $attendance->is_holiday = $holiday->contains('date_holiday', $attendance->date);
        //     $attendance->holiday_name = $attendance->is_holiday ? $holiday->where('date_holiday', $attendance->date)->first()->name_holiday : null;
        // });

        //dd($attendances->pluck('date', 'name_holiday')->all());
    
        $attendances->each(function ($attendance) use ($holiday) {
        $attendanceDate = date('d-m-Y', strtotime($attendance->date));
        
        $attendance->is_holiday = $holiday->contains('date_holiday', $attendanceDate);
        $attendance->holiday_name = $attendance->is_holiday ? $holiday->where('date_holiday', $attendanceDate)->first()->name_holiday : null;
        });
             
        $attendanceCounts = DB::table('attendances')
            ->select('employee_id', DB::raw('count(*) as attendance_count'))
            ->groupBy('employee_id')
            ->get();

        $curmnth = date('m');
        $curyear = date('Y');
        $totDays = $this->getDaysInMonth($curmnth, $curyear);
        $weekendCount = $this->getWeekendCount($curmnth, $curyear);
   
        $extraDaysCount = $attendances->filter(function ($attendance) {
        $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
           
        return $dayOfWeek == 6 || $dayOfWeek == 0;  //  Saturday (6) or Sunday (0)
        })->count();

        // $overtimeHours = $attendances->sum(function ($attendance) {
        //     $regularHours = 10;
        //     $punchIn = Carbon::parse($attendance->punch_in);
        //     $punchOut = Carbon::parse($attendance->punch_out);
        //     $hoursWorked = $punchOut->diffInHours($punchIn);
        //     $overtime = max($hoursWorked - $regularHours, 0);
        //     //dd($overtime);
        //     return $overtime;
        // });
        $overtimeHours = $attendances->sum(function ($attendance) {
            $regularHours = 10;

            $punchIn = Carbon::parse($attendance->punch_in);
            $punchOut = Carbon::parse($attendance->punch_out);

            $hoursWorked = $punchOut->diffInHours($punchIn);

            $overtime = max($hoursWorked - $regularHours, 0);

            return $overtime;
        });

        return view('reports.attendance-report', compact(['departments', 'employees', 'attendances',
         'attendanceCounts', 'holiday', 'curmnth', 'curyear', 'totDays', 
         'weekendCount', 'extraDaysCount','overtimeHours',]));
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

    public function attendance()
    {
        $attendance = Attendance::all();
        $attendanceCount = Attendance::count();
        $next_id = IdGenerator::generate(['table' => 'attendances', 'length' => 10, 'prefix' => 'A']);
        $employees = Employee::all();
        return view('form.attendanceemployee', compact('attendance', 'next_id', 'employees', 'attendanceCount'));
    }

    public function store(Request $request)
    {

        // Validate the form data
        $request->validate([
            'employee_id' => 'required|numeric|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'punch_in' => 'required|date_format:H:i',
            'punch_out' => 'required|date_format:H:i',
        ]);
        DB::beginTransaction();
        try {

            $attendance = Attendance::create([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'punch_in' => $request->punch_in,
                'punch_out' => $request->punch_out,
            ]);

            DB::commit();

            Toastr::success('Added attendence successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Attendance fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** update record attendance */
    public function updateAttendance(Request $request)
    {
        DB::beginTransaction();
        try {
            $id             = $request->id;
            $employeeId     = $request->employee_id; // Update field name
            $attendanceDate = $request->date; // Update field name
            $punchIn        = $request->punch_in; // Update field name
            $punchOut       = $request->punch_out; // Update field name

            // Update the attendance record
            $update = [
                'employee_id'  => $employeeId,
                'date'         => $attendanceDate,
                'punch_in'     => $punchIn,
                'punch_out'    => $punchOut,
                'attendance'   => $request->attendance,
            ];

            Attendance::where('id', $id)->update($update);
            DB::commit();
            // Use Toastr for flash messages
            Toastr::success('Record updated successfully :)', 'Success');
            return redirect()->route('form.attendance.page');
        } catch (\Exception $e) {
            DB::rollback();
            // Use Toastr for flash messages
            Toastr::error('Failed to update record :(', 'Error');
            return redirect()->back();
        }
    }
}
