<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // main dashboard
    public function index()
    {
        $employeeCount = Employee::count();
        $employees = Employee::all();

        $absentCounts = $this->getAbsentCounts($employees);
   

        return view('dashboard.dashboard', compact('employeeCount','employees','absentCounts'));
    }


        private function getAbsentCounts($employees)
    {
        $curmnth = date('m');
        $curyear = date('Y');
        $totDays = $this->getDaysInMonth($curmnth, $curyear);

        $attendanceCounts = DB::table('attendances')
            ->select('employee_id', DB::raw('count(*) as attendance_count'))
            ->groupBy('employee_id')
            ->get();

        $absentCounts = array();
        foreach ($employees as $employee) {
            $absentCount = $totDays - $attendanceCounts->where('employee_id', $employee->id)->first()->attendance_count;
            $absentCounts[$employee->id] = $absentCount;
        }

        return $absentCounts;
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













    // employee dashboard
    public function emDashboard()
    {
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate'));
    }

    public function generatePDF(Request $request)
    {
        // $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        // $pdf = PDF::loadView('payroll.salaryview', $data);
        // return $pdf->download('text.pdf');
        // selecting PDF view
        $pdf = PDF::loadView('payroll.salaryview');
        // download pdf file
        return $pdf->download('pdfview.pdf');
    }
}
