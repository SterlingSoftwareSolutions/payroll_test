<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Holiday;
use DB;
use Validator;

class AttendanceController extends Controller
{


public function attendance()
{
    
    $attendance = Attendance::all();
    $next_id = IdGenerator::generate(['table' => 'attendances', 'length' => 10, 'prefix' =>'A']);
    $employees = Employee::all();
    return view('form.attendanceemployee', compact('attendance', 'next_id', 'employees'));
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
    
        $attendence = Attendance::create([
            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'punch_in' => $request->punch_in,
            'punch_out' => $request->punch_out,
        ]);

        DB::commit();

        Toastr::success('Added attendence successfully :)','Success');
        return redirect()->back();
        
    } catch(\Exception $e) {
        DB::rollback();
        Toastr::error('Add Attendance fail :)','Error');
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
