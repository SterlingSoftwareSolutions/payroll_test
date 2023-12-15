<?php

namespace App\Http\Controllers;
use DB;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\department;
use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        $attendanceCount = Attendance::count();
        $attendance = Attendance::all();
        $departments = department::all();
        $employees = Employee::all();
       



        $query = department::query();
        //dd($query);
        // $departments = $query->with(['attendance', 'department'])->get();
        // dd($departments);
        // foreach ($departments as $key => $department) {
        //     $pickupTime = Carbon::parse($booking['pickup_time']);
        //     $dropoffTime = Carbon::parse($booking['dropoff_time']);
        //     // Calculate the difference in days
        //     $daysCount = $dropoffTime->diffInDays($pickupTime);
        //     $booking['bookingDaysCount'] = $daysCount;
        // }
        
        return view('reports.attendance-report', compact('departments','attendanceCount','attendance', 'employees'));
    }

   
    // attendance report pdf page
    public function attendanceReportPdf()
    {
        return view('reports.attendance-report-pdf');
    }


    // public function getDataByYearMonth(Request $request)
    // {
    //     $departments = department::select('department')->distinct()->get();
       

    //     $selectedDepartment = $request->input('department', null);
    //     $selectedYear = $request->input('year', date('Y'));
    //     $selectedMonth = $request->input('month', date('m'));

    //     $dataQuery = department::when($selectedDepartment, function ($query) use ($selectedDepartment) {
    //         return $query->where('department', $selectedDepartment);
    //     })
    //     ->whereYear('created_at', '=', $selectedYear)
    //     ->whereMonth('created_at', '=', $selectedMonth);

    //     $data = $dataQuery->get();

    //     return view('reports.attendance-report', compact('data', 'departments', 'selectedDepartment', 'selectedYear', 'selectedMonth'));
    // }
}
