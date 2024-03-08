<?php

namespace App\Http\Controllers;

use DB;
use Log;
use DateTime;
use Validator;
use DatePeriod;
use DateInterval;
use League\Csv\Reader;
use App\Models\CsvData;
use App\Models\Holiday;
use App\Models\Employee;
use League\Csv\Statement;
use App\Models\Attendance;

use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AttendanceController extends Controller
{
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
        // dd($request);
        // Validate the form data
        $request->validate([
            'employee_id' => 'required',
            'work_id' => 'required',
            'selected_employee_id' => 'required|numeric|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'punch_in' => 'required|date_format:H:i',
            'punch_out' => 'required|date_format:H:i',
        ]);
        DB::beginTransaction();
        try {

            $attendance = Attendance::create([
                'employee_id' => $request->selected_employee_id,
                'work_id' => $request->work_id,
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
        //  dd($request)->all();
        DB::beginTransaction();
        try {
            $attendance_id = $request->attendance_id;
            $employee_id = $request->employee_id;
            $date = $request->date;
            $punch_in = $request->punch_in;
            $punch_out = $request->punch_out;

            // Update the attendance record
            $update = [
                'employee_id'  => $employee_id,
                'date'         => $date,
                'punch_in'     => $punch_in,
                'punch_out'    => $punch_out,
            ];

            Attendance::where('id', $attendance_id)->update($update);
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

    public function download(Employee $employee)
    {
        $data = $employee->attendance_data();
        $data['employee'] = $employee;
        $pdf = Pdf::loadView('pdf', $data)->setPaper('a5', 'landscape');;
        return $pdf->download();
    }

    public function attendanceSearch(Request $request)
    {
        // dd($request->all());
        $next_id = IdGenerator::generate(['table' => 'attendances', 'length' => 10, 'prefix' => 'A']);
        $attendance = DB::table('attendances')->get();
        $employees = Employee::all();

        if ($attendance != null) {
            $attendance = Attendance::where('employee_id', 'LIKE', '%' . $request->employee_id . '%')->get();
        }
        if ($request->has('month')) {
            $month = $request->month;

            $attendance = Attendance::whereMonth('date', '=', $month)->get();

            // Now $attendance contains all the attendance records for the specified month
        }
        if ($request->has('select_year')) {
            $year = $request->select_year;
            if ($year != null) {
                // Validate if $year is a valid four-digit year
                if (strlen($year) === 4 && is_numeric($year)) {
                    $attendance = Attendance::whereYear('date', '=', $year)->get();

                    // Now $attendance contains all the attendance records for the specified year
                } else {
                    // Handle invalid year, perhaps return an error response
                    return response()->json(['error' => 'Invalid year format'], 400);
                }
            }
        }
        if ($attendance !== null && $request->has('month')) {
            $month = $request->month;
            $employeeId = $request->employee_id;

            $attendance = Attendance::whereMonth('date', '=', $month)
                ->where('employee_id', 'LIKE', '%' . $employeeId . '%')
                ->get();
        }
        if ($attendance !== null && $request->has('select_year')) {
            $year = $request->select_year;
            $employeeId = $request->employee_id;

            if ($year != null) {
                // Validate if $year is a valid four-digit year
                if (strlen($year) === 4 && is_numeric($year)) {
                    $attendance = Attendance::whereYear('date', '=', $year)
                        ->where('employee_id', 'LIKE', '%' . $employeeId . '%')
                        ->get();
                } else {
                    // Handle invalid year, perhaps return an error response
                    return response()->json(['error' => 'Invalid year format'], 400);
                }
            }
        }
        if ($request->has('month') && $request->has('select_year')) {
            $month = $request->month;
            $year = $request->select_year;

            if ($year != null) {
                if (strlen($year) === 4 && is_numeric($year)) {
                    $attendance = Attendance::whereMonth('date', '=', $month)
                        ->whereYear('date', '=', $year)
                        ->get();
                } else {
                    // Handle invalid year, perhaps return an error response
                    return response()->json(['error' => 'Invalid year format'], 400);
                }
            }
        }
        return view('form.Attendanceemployee', compact('attendance', 'next_id', 'employees'));
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
        $departments = Department::all();
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

    public function showUploadForm()
    {
        return view('form.attendanceemployee');
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file'
        ]);

        // Parse the CSV
        $entries = array_map('str_getcsv', file($request->csv_file->getRealPath()));
        $headers = array_shift($entries);
        $attendances = [];
        $errors = [];

        // Attendances grouped by date.
        // First punch_in of the day will be taken as punch_in, and the last one as punch_out.
        foreach ($entries as $row) {
            $entry = array_combine($headers, $row);
            if(isset( $attendances [$entry['Date']] [$entry['WorkId']] ['punch_in'])){
                $attendances [$entry['Date']] [$entry['WorkId']] ['punch_out'] = $entry['punch_in'];
            } else{
                $attendances [$entry['Date']] [$entry['WorkId']] ['punch_in'] = $entry['punch_in'];
            }
        }

        foreach($attendances as $date => $attendances_current_day){
            foreach ($attendances_current_day as $WorkId => $attendance) {
                $employee = Employee::where('work_id', $WorkId)->first();

                // Check if employee exists in the system
                if(!$employee){
                    $errors [$date] [$WorkId] = "Employee not found.";
                    continue;
                }

                // Check if the employee has punched out
                if(!isset($attendance['punch_out'])){
                    $errors [$date] [$WorkId] = "$employee->f_name - Punch out time not found.";
                    continue;
                }

                // Create attendance entry
                $attendance = Attendance::updateOrCreate([
                    'employee_id' => $employee->id,
                    'date' => Carbon::parse($date)
                ],[
                    'WorkId' => $WorkId,
                    'punch_in' => $attendance['punch_in'],
                    'punch_out' => $attendance['punch_out'],
                ]);
            }
        }

        return back()->with('import_errors', $errors);
    }

    private function processCsv($filePath)
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0); 

        $stmt = (new Statement())->offset(0); 

    
        $data = $stmt->process($csv);

    
        Log::debug('Processed CSV data: ' . json_encode(iterator_to_array($data)));

        return iterator_to_array($data); 
    }
}