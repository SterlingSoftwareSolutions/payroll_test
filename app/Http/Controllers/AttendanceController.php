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
        'date' => 'required|date',
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
        Toastr::error('Add Holiday fail :)','Error');
        return redirect()->back();
    }
}
}
