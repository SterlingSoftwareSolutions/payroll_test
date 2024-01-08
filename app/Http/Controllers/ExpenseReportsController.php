<?php

namespace App\Http\Controllers;
use DB;

use App\Models\department;
use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use App\Models\Employee;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        $departments = department::all();
        return view('reports.attendance-report', compact('departments'));
    }

   
    // attendance report pdf page
    public function attendanceReportPdf()
    {
        return view('reports.attendance-report-pdf');
    }

    public function showDepartmentView()
    {
        $departments = department::all();
        return view('reports.attendance-report', compact('departments'));
    }


   












    // leave reports page
    // public function leaveReport()
    // {
    //     $leaves = DB::table('leaves_admins')
    //                 ->join('users', 'users.user_id', '=', 'leaves_admins.user_id')
    //                 ->select('leaves_admins.*', 'users.*')
    //                 ->get();
    //     return view('reports.leavereports',compact('leaves'));
    // }
}
