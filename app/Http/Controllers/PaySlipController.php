<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\department;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class PaySlipController extends Controller
{
    public function index(){

        $query = Employee::query();
        $employees = $query->get();


        return view('reports/payslip-approve', compact(['employees']));


    }


     // update
     public function updateRecord( Request $request)
     {
        //dd($request);
         DB::beginTransaction();
         try{
             $id           = $request->id;
             $incentive  = $request->incentive;
             $late_deduction  = $request->late_deduction;

             $update = [

                 'id'           => $id,
                 'incentive' => $incentive,
                 'late_deduction' => $late_deduction,
             ];
             pay_slip::where('id',$request->id)->update($update);
             DB::commit();
             Toastr::success('Payslip updated successfully :)','Success');
             return redirect()->back();

         }catch(\Exception $e){
             DB::rollback();
             Toastr::error('payslip update fail :)','Error');
             return redirect()->back();
         }
     }



        public function downloardfile(){
            $file=public_path('files/sample.pdf');
            return response()->download($file);
        }




    public function get_salary_report(){

        $query = Employee::query();
        $employees = $query->get();

        $departments = department::all();
        return view('reports/salary-report', compact('departments','employees'));
    }

    public function getDetails($employeeId)
{
    $employee = Employee::where('employee_id', $employeeId)->first();
    $basicSalary = $employee->basic_Salary;
    $brAllowance = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'BR allowance')
                                ->where('type', 'increments')
                                ->sum('increment_amount');
    $incentive1 = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Incentive 1')
                                ->where('type', 'increments')
                                ->sum('increment_amount');
    $incentive2 = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Incentive 2')
                                ->where('type', 'increments')
                                ->sum('increment_amount');
    $increment_others = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Others')
                                ->where('type', 'increments')
                                ->sum('increment_amount');
    $bodim = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Bodim')
                                ->where('type', 'deductions')
                                ->sum('increment_amount');
    $Others = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Others')
                                ->where('type', 'deductions')
                                ->sum('increment_amount');
    $deduction_others = $bodim + $Others;
    $Advanced = SalaryDetail::where('employee_id', $employeeId)
                                ->where('increment_name', 'Advanced')
                                ->where('type', 'deductions')
                                ->sum('increment_amount');
        // $GrossSalary = $brAllowance + $basicSalary;
        // $dayspay = $GrossSalary / 30;
        // $halfday = $dayspay / 2;
        // $NoPayDeduction = 2 * $dayspay;



    return response()->json(['basic_salary' => $basicSalary, 'brAllowance' => $brAllowance ,
    'incentive1' => $incentive1 , 'incentive2' => $incentive2 , 'increment_others' => $increment_others ,
    'deduction_others' => $deduction_others , 'Advanced' => $Advanced]);
}


}