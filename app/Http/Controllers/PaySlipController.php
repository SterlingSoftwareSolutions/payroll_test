<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;

class PaySlipController extends Controller
{
    public function index(){
        return view('reports/payslip-approve');

    }

    public function get_salary_report(){
        return view('reports/salary-report');
    }
}
