<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller; 
use DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Employee;
use App\Models\department;
use App\Models\User;
use App\Models\module_permission;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class EmployeeController extends Controller
{
    public function submitEmployeeForm(Request $request)
{
    // Handle personal details form submission logic here
    // ...

    return redirect()->back()->with('success', 'Employee form submitted successfully!');
}
public function submitBankForm(Request $request)
{
    // Handle bank details form submission logic here
    // ...

    return redirect()->back()->with('success', 'Bank details submitted successfully!');
}
public function submitSalaryForm(Request $request)
{
    // Handle salary details form submission logic here
    // ...

    return redirect()->back()->with('success', 'Salary details submitted successfully!');
}


    // all employee list
    public function listAllEmployee()
    {
        $users = DB::table('users')
                    ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.dob', 'employees.gender')
                    ->get();
                
        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $employees = Employee::all();
      
        $departments = department::all();
        
        return view('form.employeelist', compact('employees','departments'));
    }
    // Add employee view
    public function createEmployee(){

        $departments = department::all();
        

        return view('form.employeeform', compact('departments'));

    }


    // save data employee
    
    public function saveRecord(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        // Add validation rules for each form field
        'full_name' => 'required',
        'email' => 'required|email',
        'c_number' => 'required',
        'j_title' => 'required',
        // Add other fields as needed
    ]);

    // Create a new employee
    $employee = new Employee;

    $employee->employee_id = $request->input('employee_id');
    $employee->d_name = $request->input('department');
    $employee->f_name = $request->input('f_name');
    $employee->l_name = $request->input('l_name');
    $employee->full_name = $request->input('full_name');
    $employee->dob = $request->input('dob');
    $employee->gender = $request->input('gender');
    $employee->email = $request->input('email');
    $employee->nic= $request->input('nic');
    $employee->c_number = $request->input('c_number');
    $employee->j_title = $request->input('j_title');
    $employee->joinedDate = $request->input('joinedDate');
    $employee->createdDate = today();
    $employee->status = $request->input('status');
    $employee->description = $request->input('description');
    $employee->account_name = $request->input('account_name');
    $employee->account_number = $request->input('account_number');
    $employee->bank_name = $request->input('bank_name');
    $employee->branch = $request->input('branch');
    // Set other fields

    // Save the employee to the database
    $employee->save();

    return redirect()->route('all/employee/list');
}
    // view edit record
    public function viewRecord($employee_id)
    {
        $permission = DB::table('employees')
            ->join('module_permissions', 'employees.employee_id', '=', 'module_permissions.employee_id')
            ->select('employees.*', 'module_permissions.*')
            ->where('employees.employee_id','=',$employee_id)
            ->get();
        $employees = DB::table('employees')->where('employee_id',$employee_id)->get();
       
        return view('form.edit.editemployee',compact('employees','permission'));
    }
    // update record employee
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            // update table Employee
            $updateEmployee = [
                'employee_id'=>$request->employee_id,
                'd_name'=>$request->d_name,
                'f_name'=>$request->f_name,
                'l_name'=>$request->l_name,
                'full_name'=>$request->full_name,
                'dob'=>$request->dob,
                'gender'=>$request->gender,
                'email'=>$request->email,
                'nic'=>$request->nic,
                'c_number'=>$request->c_number,
                'j_title'=>$request->j_title,
                'joinedDate'=>$request->joinedDate,
                'createdDate'=>$request->createdDate,
                'status'=>$request->status,
                'description'=>$request->description,
                'account_name'=>$request->account_name,
                'account_number'=>$request->account_number,
                'bank_name'=>$request->bank_name,
                'branch'=>$request->branch,
            ];
            // update table user
            $updateUser = [
                'employee_id'=>$request->employee_id,
                'full_name'=>$request->full_name,
                'email'=>$request->email,
            ];

            // update table module_permissions
            for($i=0;$i<count($request->employee_id_permission);$i++)
            {
                $UpdateModule_permissions = [
                    'employee_id' => $request->employee_id,
                    'module_permission' => $request->permission[$i],
                    'id'                => $request->id_permission[$i],
                    'read'              => $request->read[$i],
                    'write'             => $request->write[$i],
                    'create'            => $request->create[$i],
                    'delete'            => $request->delete[$i],
                    'import'            => $request->import[$i],
                    'export'            => $request->export[$i],
                ];
                module_permission::where('id',$request->id_permission[$i])->update($UpdateModule_permissions);
            }

            User::where('id',$request->employee_id)->update($updateUser);
            Employee::where('employye_id',$request->employye_id)->update($updateEmployee);
        
            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->route('all/employee/list');
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('updated record fail :)','Error');
            return redirect()->back();
        }
    }
    // delete record
    public function deleteRecord($employee_id)
    {
        DB::beginTransaction();
        try{

            Employee::where('employee_id',$employee_id)->delete();
            module_permission::where('employee_id',$employee_id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->route('all/employee/list');

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }
    // employee search
    public function employeeSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.dob', 'employees.gender')
                    ->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')
                        ->get();
        }
         // search by name and position and id
         if($request->employee_id && $request->name && $request->position)
         {
             $users = DB::table('users')
                         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                         ->select('users.*', 'employees.dob', 'employees.gender')
                         ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                         ->where('users.name','LIKE','%'.$request->name.'%')
                         ->where('users.position','LIKE','%'.$request->position.'%')
                         ->get();
         }
        return view('form.allemployeecard',compact('users','userList','permission_lists'));
    }
    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                    ->select('users.*', 'employees.dob', 'employees.gender')
                    ->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by department
        if($request->department)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.department','LIKE','%'.$request->department.'%')
                        ->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by department and id
        if($request->employee_id && $request->department)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.department','LIKE','%'.$request->department.'%')
                        ->get();
        }
        // search by name and department
        if($request->name && $request->department)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.department','LIKE','%'.$request->department.'%')
                        ->get();
        }
        // search by name and department and id
        if($request->employee_id && $request->name && $request->department)
        {
            $users = DB::table('users')
                        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                        ->select('users.*', 'employees.dob', 'employees.gender')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.department','LIKE','%'.$request->department.'%')
                        ->get();
        }
        return view('form.employeelist',compact('users','userList','permission_lists'));
    }

    // employee profile with all controller user
    public function profileEmployee($user_id)
    {
        $users = DB::table('users')
                ->leftJoin('personal_information','personal_information.user_id','users.user_id')
                ->leftJoin('profile_information','profile_information.user_id','users.user_id')
                ->where('users.user_id',$user_id)
                ->first();
        $user = DB::table('users')
                ->leftJoin('personal_information','personal_information.user_id','users.user_id')
                ->leftJoin('profile_information','profile_information.user_id','users.user_id')
                ->where('users.user_id',$user_id)
                ->get(); 
        return view('form.employeeprofile',compact('user','users'));
    }

    /** page departments */
    public function index()
    {
        $departments = Department::all();
       
        $next_id = IdGenerator::generate(['table' => 'departments', 'length' => 7, 'prefix' => 'D']);
        return view('form.departments', compact('departments', 'next_id'));
        
    }
  

    /** save record department */
    public function saveRecordDepartment(Request $request)
    {
            $request->validate([
            'department' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try{

            $department = department::where('department',$request->department)->first();
            if ($department === null)
            {
                $department = department::create([
                    'id' => IdGenerator::generate(['table' => 'departments', 'length' => 7, 'prefix' => 'D']),
                    'department' => $request->department,
                ]);
             
                DB::commit();
                Toastr::success('Add new department successfully :)','Success');
                return redirect()->route('form/departments/page');
            } else {
                DB::rollback();
                Toastr::error('Add new department exits :)','Error');
                return redirect()->back();
            }
            }catch(\Exception $e){
                DB::rollback();
                Toastr::error('Add new department fail :)','Error');
                return redirect()->back();
            }
        ;
    
    }
     

    /** update record department */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try{      
            // update table departments
            $department = [              
                'id' => IdGenerator::generate(['table' => 'departments', 'length' => 7, 'prefix' => 'D']),
                'department' => $request->department,
            ];  
            department::where('id',$request->id)->update($department);
        
            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->route('form/departments/page');
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('updated record fail :)','Error');
            return redirect()->back();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request) 
    {
        try {

            department::destroy($request->id);
            Toastr::success('Department deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Department delete fail :)','Error');
            return redirect()->back();
        }
    }

    /** page designations */
    public function designationsIndex()
    {
        return view('form.designations');
    }

    /** page time sheet */
    public function timeSheetIndex()
    {
        return view('form.timesheet');
    }

    /** page overtime */
    public function overTimeIndex()
    {
        return view('form.overtime');
    }
    // EmployeeController.php

    public function getEmployeeData()
    {
        $employees = Employee::all(); // Adjust this query based on your needs

        return response()->json(['employees' => $employees]);
    }
    public function someMethod()
    {
        $url = route('save.record'); // Include the correct namespace
    }
}


