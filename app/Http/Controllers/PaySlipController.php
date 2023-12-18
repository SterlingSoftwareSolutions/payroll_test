<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\department;
use Illuminate\Http\Request;

class PaySlipController extends Controller
{
    public function index(){

        $query = Employee::query();
        $employees = $query->get();
    

        return view('reports/payslip-approve', compact(['employees']));
     

    }

    public function get_salary_report(){
        return view('reports/salary-report');
    }
}
