<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Validator;
use DatePeriod;
use DateInterval;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\department;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $current_month = $request->month ?? date('m');
        $current_year = $request->year ?? date('Y');

        $employees = $query->get();
        $departments = department::all();
        $holiday = Holiday::all();
        $attendances = Attendance::with('employee', 'holiday')->whereMonth('date', $current_month)->whereYear('date', $current_year)->get();

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


        
        $departments = department::select('id','department')->distinct()->get();

        $department = $request->input('department', null);                  //for search feature
        $selectedYear = $request->input('year', date('Y'));
        $selectedMonth = $request->input('month', date('m'));

        $dataQuery = department::when($department, function ($query) use ($department) {
            return $query->where('department', $department);
        })
   
        ->whereYear('created_at', '=', $selectedYear)       
        ->whereMonth('created_at', '=', $selectedMonth);

        $data = $dataQuery->get();

        

        return view('reports.attendance-report', compact([
            'departments', 'employees', 'attendances',
            'attendanceCounts', 'holiday', 'current_month', 'current_year', 'totDays',
            'weekendCount', 'extraDaysCount', 'employeeHolidayCounts','selectedYear', 'selectedMonth','data'
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




    public function downloardAtte()
    {
        $file = public_path('files/sample.pdf');
        return response()->download($file);
    }


    public function downloadPDF(Request $request)
    {
        $attendances = $request->all();

        $request->validate([
            // ... (same validation rules as before)
        ]);

        // Generate PDF using the 'attendance.form' Blade view and data
        $pdf = PDF::loadView('reports.attendance-report', compact('attendances'));

        // Download the PDF with a custom filename
        return $pdf->download('form/attendance/pdf');
    }
 
    public function download(Employee $employee) {
        $current_month = date('m');
        $current_year = date('Y');
        $total_days = $this->getDaysInMonth($current_month, $current_year);
        $attendances = Attendance::where('employee_id', $employee->id)->whereMonth('date', $current_month)->whereYear('date', $current_year);
        $weekend_days = $this->getWeekendCount($current_month, $current_year);
        $working_days = $total_days - $weekend_days;
        $attended_days = $attendances->count();
        $absent_days = $working_days - $attended_days;
        $extra_days_count = $attendances->get()->filter(function ($attendance) {
            $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
            return $dayOfWeek == 6 || $dayOfWeek == 0;  // Note Saturday (6) or Sunday (0)
        })->count();
        $holidays = Holiday::pluck('date_holiday');
        $holiday_working_count = $attendances->whereIn('date', $holidays)->count();
        
        $pdf = Pdf::loadView('pdf', compact(
            'employee',
            'attendances',
            'current_month',
            'current_year',
            'total_days',
            'weekend_days',
            'working_days',
            'attended_days',
            'absent_days',
            'extra_days_count',
            'holidays',
            'holiday_working_count',
        ))->setPaper('a5', 'landscape');;

       return $pdf->stream();
        // return $pdf->download();
    }
}
