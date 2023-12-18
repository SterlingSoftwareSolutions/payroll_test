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
    public function index(Request $request)
    {
         $attendanceCount = Attendance::count();
        $attendance = Attendance::all();
        $departments = Department::all();
       // dd($departments);
        $employees = Employee::all();
        $selectedDepartment = Department::find($request->department);
        // Check if the department is found before accessing the relationship
        if ($selectedDepartment) {
            $hasEmployees = $selectedDepartment->employees()->exists();
        } else {
            $hasEmployees = false;
        }

      // $departments = department::with('employees')->get();

     // $departments = department::with('employees', 'attendances')->get();

        //$query = department::query();
  
        // foreach ($departments as $key => $department) {
        //     $pickupTime = Carbon::parse($booking['pickup_time']);
        //     $dropoffTime = Carbon::parse($booking['dropoff_time']);
        //     // Calculate the difference in days
        //     $daysCount = $dropoffTime->diffInDays($pickupTime);
        //     $booking['bookingDaysCount'] = $daysCount;
        // }
        
        return view('reports.attendance-report', compact('departments','attendanceCount','attendance', 'employees','hasEmployees','selectedDepartment'));
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
