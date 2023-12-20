<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
