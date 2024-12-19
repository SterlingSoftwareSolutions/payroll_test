<?php

namespace App\Http\Controllers;

use DB;
use Log;
use DateTime;
use Validator;
use DatePeriod;
use DateInterval;
use DateTimeZone;
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
use App\Models\AttendanceReport;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
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
        $employee = $employees->first();
        // dd($employees);
        return view('form.attendanceemployee', compact('attendance', 'next_id', 'employees', 'attendanceCount', 'employee'));
    }

    public function store(Request $request)
    {
        // dd($request);
        // Validate the form data
        $request->validate([
            'employee_id' => 'required',
            // 'work_id' => 'required',
            'selected_employee_id' => 'required|numeric|exists:employees,id',
            'date' => 'required|date|date_format:Y-m-d',
            'punch_in' => 'required|date_format:H:i',
            'punch_out' => 'required|date_format:H:i',
        ]);
        $work_id = Employee::where('id', $request->selected_employee_id)->value('work_id');
        $punchIn = new DateTime($request->punch_in);
        $punchOut = new DateTime($request->punch_out);

        $employee = Employee::where('id', $request->selected_employee_id)->first();

        // dd($employee);
        $punchOut = new DateTime($request->punch_out);
        $punchIn = new DateTime($request->punch_in);
        $workHours = $punchOut->diff($punchIn)->format('%H:%I');
        // dd($workHours);
        $dateTime = new DateTime($request->date);
        $dayOfWeek = $dateTime->format('l');

        $OT = '00:00';
        $late = '00:00';

        if ($employee->workingHours == '6day' && $dayOfWeek == 'Sunday') {
            // dd($employee->workingHours);
            $otStartTime = new DateTime('00:00');
            $workHoursTime = new DateTime($workHours);

            if ($workHoursTime > $otStartTime) {
                $otInterval = $workHoursTime->diff($otStartTime);
                $OT = $otInterval->format('%H:%I');
                // dd($OT);
            }
        } elseif ($employee->workingHours == '6day' && $dayOfWeek == 'Sunday') {
            $otStartTime = new DateTime('05:00');
            $workHoursTime = new DateTime($workHours);

            if ($workHoursTime > $otStartTime) {
                $otInterval = $workHoursTime->diff($otStartTime);
                $OT = $otInterval->format('%H:%I');
            } else {
                $lateInterval = $otStartTime->diff($workHoursTime);
                $late = $lateInterval->format('%H:%I');
            }
        } elseif ($employee->workingHours == '5day' && in_array($dayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
            $otStartTime = new DateTime('10:00');
            $workHoursTime = new DateTime($workHours);

            if ($workHoursTime > $otStartTime) {
                $otInterval = $workHoursTime->diff($otStartTime);
                $OT = $otInterval->format('%H:%I');
            } else {
                $lateInterval = $otStartTime->diff($workHoursTime);
                $late = $lateInterval->format('%H:%I');
            }
        } elseif ($employee->workingHours == '6day' && in_array($dayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
            $otStartTime = new DateTime('09:00');
            $workHoursTime = new DateTime($workHours);

            if ($workHoursTime > $otStartTime) {
                $otInterval = $workHoursTime->diff($otStartTime);
                $OT = $otInterval->format('%H:%I');
            } else {
                $lateInterval = $otStartTime->diff($workHoursTime);
                $late = $lateInterval->format('%H:%I');
            }
        }
        $is_half_day = $workHoursTime <= 6 && $workHoursTime >= 4;

        // dd($workHours);
        DB::beginTransaction();
        try {
            $attendance = Attendance::create([
                'employee_id' => $request->selected_employee_id,
                'WorkId' => $work_id,
                'date' => $request->date,
                'punch_in' => $request->punch_in,
                'punch_out' => $request->punch_out,
                'workHours' => $workHours,
                'OT' => $OT,
                'late' => $late,
                'is_half_day' => $is_half_day,
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

            $punchIn = new DateTime($request->punch_in);
            $punchOut = new DateTime($request->punch_out);

            $employee = Employee::where('id', $request->employee_id)->first();

            // dd($employee);
            $punchOut = new DateTime($request->punch_out);
            $punchIn = new DateTime($request->punch_in);
            $workHours = $punchOut->diff($punchIn)->format('%H:%I');
            
            // dd($workHours);
            $dateTime = new DateTime($request->date);
            $dayOfWeek = $dateTime->format('l');

            $OT = '00:00';
            $late = '00:00';

            if ($employee->workingHours == '5day' && ($dayOfWeek == 'Sunday' || $dayOfWeek == 'Saturday')) {
                // Your code here
            } elseif ($employee->workingHours == '6day' && $dayOfWeek == 'Sunday') {
                // Your code here
            } elseif ($employee->workingHours == '6day' && $dayOfWeek == 'Sunday') {
                $otStartTime = new DateTime('05:00');
                $workHoursTime = new DateTime($workHours);

                if ($workHoursTime > $otStartTime) {
                    $otInterval = $workHoursTime->diff($otStartTime);
                    $OT = $otInterval->format('%H:%I');
                } else {
                    $lateInterval = $otStartTime->diff($workHoursTime);
                    $late = $lateInterval->format('%H:%I');
                }
            } elseif ($employee->workingHours == '5day' && in_array($dayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
                $otStartTime = new DateTime('10:00');
                $workHoursTime = new DateTime($workHours);

                if ($workHoursTime > $otStartTime) {
                    $otInterval = $workHoursTime->diff($otStartTime);
                    $OT = $otInterval->format('%H:%I');
                } else {
                    $lateInterval = $otStartTime->diff($workHoursTime);
                    $late = $lateInterval->format('%H:%I');
                }
            } elseif ($employee->workingHours == '6day' && in_array($dayOfWeek, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
                $otStartTime = new DateTime('09:00');
                $workHoursTime = new DateTime($workHours);

                if ($workHoursTime > $otStartTime) {
                    $otInterval = $workHoursTime->diff($otStartTime);
                    $OT = $otInterval->format('%H:%I');
                } else {
                    $lateInterval = $otStartTime->diff($workHoursTime);
                    $late = $lateInterval->format('%H:%I');
                }
            }
            $is_half_day = $workHoursTime <= 6 && $workHoursTime >= 4;

            // Update the attendance record
            $update = [
                'employee_id'  => $employee_id,
                'date'         => $date,
                'punch_in'     => $punch_in,
                'punch_out'    => $punch_out,
                'workHours' => $workHours,
                'OT' => $OT,
                'late' => $late,
                'is_half_day' => $is_half_day,
            ];
            // dd($attendance_id);

            Attendance::where('id', $attendance_id)->update($update);
            DB::commit();
            // Use Toastr for flash messages
            Toastr::success('Record updated successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            // dd($e);
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

    public function download($employee_id, $report_id)
    {
        $employee = Employee::find($employee_id);
        $report = AttendanceReport::find($report_id);

        $attendances = Attendance::where('employee_id', $report->employee_id)
            ->whereYear('date', date('Y', strtotime($report->date)))
            ->whereMonth('date', date('m', strtotime($report->date)))
            ->get()
            ->toArray();


        if (!$employee || !$report) {
            // Handle the case where employee or report is not found
            abort(404, 'Employee or report not found');
        }
        // dd( $attendances);
        $data = ['employee' => $employee, 'report' => $report, 'attendances' => $attendances];
        $pdf = PDF::loadView('pdf', $data)->setPaper('a5', 'landscape');
        $fileName = strtoupper(preg_split('#\s+#', $employee->full_name)[0]) . '.pdf';
        return $pdf->download($fileName);
    }



    public function attendanceSearch(Request $request)
    {
        // dd($request->all());
        $next_id = IdGenerator::generate(['table' => 'attendances', 'length' => 10, 'prefix' => 'A']);
        $attendance = DB::table('attendances')->get();
        $employees = Employee::all();
        // dd($employees);

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
        return view('form.attendanceemployee', compact('attendance', 'next_id', 'employees'));
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

        $headers = array_shift($entries); // Extract the headers
        $attendances = [];
        $errors = [];

        // Process the entries into grouped attendance data
        foreach ($entries as $row) {
            $entry = array_combine($headers, $row);

            if (isset($attendances[$entry['Date']][$entry['WorkId']]['punch_in'])) {
                $attendances[$entry['Date']][$entry['WorkId']]['punch_out'] = $entry['punch_in'];
            } else {
                $attendances[$entry['Date']][$entry['WorkId']]['punch_in'] = $entry['punch_in'];
                $attendances[$entry['Date']][$entry['WorkId']]['punch_out'] = null; // Initialize punch_out
            }
        }

        try {
            foreach ($attendances as $date => $attendances_current_day) {
                foreach ($attendances_current_day as $WorkId => $attendance) {

                    $punchIn = Carbon::parse($attendance['punch_in']);
                    $punchOut = isset($attendance['punch_out']) ? Carbon::parse($attendance['punch_out']) : null;

                    $employee = Employee::where('work_id', $WorkId)->first();

                    // Validate employee existence
                    if (!$employee) {
                        $errors[$date][$WorkId] = "Employee with Work ID $WorkId not found.";
                        continue;
                    }

                    // Check if punch_out exists and work hours are >= 2
                    if ($punchOut && $punchIn->diffInHours($punchOut) >= 4) {

                        // $is_half_day = $punchOut && $punchIn->diffInHours($punchOut) <= 6;
                        // Calculate work hours
                        $workHours = $punchOut->diff($punchIn)->format('%H:%I');
                        $dateTime = new DateTime($date);
                        $dayOfWeek = $dateTime->format('l');
                        $isWeekend = $dayOfWeek === 'Saturday' || $dayOfWeek === 'Sunday';
                        
                        $is_half_day = $punchOut && $punchIn->diffInHours($punchOut) <= 6 && !in_array($dayOfWeek, ['Saturday', 'Sunday']);

                        $holidays = Holiday::all()->pluck('date_holiday')->map->format('Y-m-d');

                        // Calculate OT and late hours
                        list($OT, $late) = $this->calculateOvertimeAndLateHours($employee, $workHours, $dayOfWeek, $holidays, $isWeekend);

                        // Create or update attendance entry
                        Attendance::updateOrCreate([
                            'employee_id' => $employee->id,
                            'date' => Carbon::parse($date)
                        ], [
                            'WorkId' => $WorkId,
                            'punch_in' => $attendance['punch_in'],
                            'punch_out' => $attendance['punch_out'],
                            'workHours' => $workHours,
                            'OT' => $OT,
                            'late' => $late,
                            'is_half_day' => $is_half_day,
                        ]);
                    } else {
                        $errors[$date][$WorkId] = "{$employee->f_name} - Punch out time not found or work hours less than 2 Hours.";
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Error processing CSV upload: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['An error occurred during the import process.']);
        }

        return back()->with('import_errors', $errors);
    }




    // public function uploadCsv(Request $request)
    // {
    //     $request->validate([
    //         'csv_file' => 'required|file'
    //     ]);

    //     // Parse the CSV
    //     $entries = array_map('str_getcsv', file($request->csv_file->getRealPath()));
    //     $headers = array_shift($entries);
    //     $attendances = [];
    //     $errors = [];

    //     // Attendances grouped by date
    //     foreach ($entries as $row) {
    //         $entry = array_combine($headers, $row);

    //         // Normalize the Date format to YYYY-MM-DD for Carbon parsing
    //         $date = $this->normalizeDate($entry['Date']);
    //         if (!$date) {
    //             // Try parsing with alternative formats
    //             $alternativeFormats = [
    //                 'd/m/Y',    // Example: 09/12/2024
    //                 'm-d-Y',    // Example: 12-09-2024
    //                 'Y/m/d',    // Example: 2024/12/09
    //             ];

    //             // Loop through alternative formats
    //             foreach ($alternativeFormats as $format) {
    //                 $date = $this->normalizeDate($entry['Date'], $format);
    //                 if ($date) {
    //                     break; // Successfully parsed, exit loop
    //                 }
    //             }

    //             // If no valid date format found, use a fallback date or handle the error
    //             if (!$date) {
    //                 $date = '1970-01-01';  // Default/fallback date
    //                 $errors[$entry['Date']] = "Invalid date format, using fallback: {$entry['Date']}";
    //             }
    //         }

    //         // Continue processing the data
    //         $entry['Date'] = $date;  // Assign the valid or fallback date to the entry

    //         if (isset($attendances[$date][$entry['WorkId']]['punch_in'])) {
    //             $attendances[$date][$entry['WorkId']]['punch_out'] = $entry['punch_in'];
    //         } else {
    //             $attendances[$date][$entry['WorkId']]['punch_in'] = $entry['punch_in'];
    //             $attendances[$date][$entry['WorkId']]['punch_out'] = null; // Initialize punch_out
    //         }
    //     }

    //     try {
    //         foreach ($attendances as $date => $attendances_current_day) {
    //             foreach ($attendances_current_day as $WorkId => $attendance) {
    //                 $punchIn = Carbon::parse($attendance['punch_in']);
    //                 $punchOut = isset($attendance['punch_out']) ? Carbon::parse($attendance['punch_out']) : null;

    //                 $employee = Employee::where('work_id', $WorkId)->first();

    //                 if ($punchOut && $punchIn->diffInHours($punchOut) >= 2) {
    //                     // Validate employee existence
    //                     if (!$employee) {
    //                         $errors[$date][$WorkId] = "Employee not found.";
    //                         continue;
    //                     }

    //                     // Calculate work hours
    //                     $workHours = $punchOut->diff($punchIn)->format('%H:%I');
    //                     $dateTime = new DateTime($date);
    //                     $dayOfWeek = $dateTime->format('l');
    //                     $isWeekend = $dayOfWeek === 'Saturday' || $dayOfWeek === 'Sunday';
    //                     $holidays = Holiday::all()->pluck('date_holiday')->map->format('Y-m-d');

    //                     // Calculate OT and late hours
    //                     list($OT, $late) = $this->calculateOvertimeAndLateHours($employee, $workHours, $dayOfWeek, $holidays, $isWeekend);

    //                     // Create or update attendance entry
    //                     Attendance::updateOrCreate([
    //                         'employee_id' => $employee->id,
    //                         'date' => Carbon::parse($date)
    //                     ], [
    //                         'WorkId' => $WorkId,
    //                         'punch_in' => $attendance['punch_in'],
    //                         'punch_out' => $attendance['punch_out'],
    //                         'workHours' => $workHours,
    //                         'OT' => $OT,
    //                         'late' => $late,
    //                     ]);
    //                 } else {
    //                     $errors[$date][$WorkId] = "$employee->f_name - Punch out time not found or work hours less than 2 Hours.";
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         return $e->getMessage();
    //     }

    //     return back()->with('import_errors', $errors);
    // }




    // Function to normalize date format
    private function normalizeDate($date)
    {
        // Try to parse the date using multiple formats
        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (Exception $e) {
            // Return null if the date format is invalid
            return null;
        }
    }

    private function calculateOvertimeAndLateHours($employee, $workHours, $dayOfWeek, $holidays, $isWeekend)
    {
        $OT = '00:00';
        $late = '00:00';
        $workHoursTime = new DateTime($workHours);

        // if ($isWeekend || $holidays->contains($dayOfWeek)) {

        //     $otStartTime = new DateTime('00:00');
        //     if ($workHoursTime > $otStartTime) {
        //         $OT = $workHoursTime->diff($otStartTime)->format('%H:%I');
        //     }

        // } else {
        // dd($employee, $workHours, $dayOfWeek, $holidays, $isWeekend);

        switch ($employee->workingHours) {


            case '6day':

                if ($dayOfWeek == 'Sunday') {
                    $otStartTime = new DateTime('00:00');
                } elseif ($dayOfWeek == 'Saturday') {
                    // dd($employee, $workHours, $dayOfWeek, $holidays, $isWeekend);
                    $otStartTime = new DateTime('05:00');
                } else {
                    $otStartTime = new DateTime('09:00');
                }
                break;
            case '5day':
                if ($dayOfWeek == 'Sunday') {
                    $otStartTime = new DateTime('00:00');
                } elseif ($dayOfWeek == 'Saturday') {
                    // dd($employee, $workHours, $dayOfWeek, $holidays, $isWeekend);
                    $otStartTime = new DateTime('00:00');
                } else {
                    $otStartTime = new DateTime('10:00');
                }
                break;
            default:
                $otStartTime = new DateTime('09:00');
                break;
        }

        if ($workHoursTime > $otStartTime) {
            $OT = $workHoursTime->diff($otStartTime)->format('%H:%I');
        } else {
            $late = $otStartTime->diff($workHoursTime)->format('%H:%I');
        }
        // }

        return [$OT, $late];
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
