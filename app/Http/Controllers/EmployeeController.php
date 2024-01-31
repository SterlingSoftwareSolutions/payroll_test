<?php

namespace App\Http\Controllers;

use DB;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\department;
use App\Models\SalaryDetail;
use Illuminate\Http\Request;
use App\Models\module_permission;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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
    public function listAllEmployee(Request $request)
    {
        $users = DB::table('users')
        ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        ->select('users.*', 'employees.dob', 'employees.gender')
        ->get();


    

        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $employees = Employee::all();
       
        $departments = department::all();

    $query = Employee::query();

   

        if ($request->has('employee_id')) {
            $query->where('employee_id', 'like', '%'. $request->input('employee_id') . '%');
        }

        if ($request->has('full_name')) {
            $query->where('full_name', 'like', '%' . $request->input('full_name') . '%');
        }

        $employees = $query->get();
    

       

        return view('form.employeelist', compact('employees', 'departments'));
    }
    // Add employee view
    public function createEmployee()
    {

        $departments = Department::pluck('department', 'id');
        $maxId = \App\Models\Employee::max('employee_id');

        if ($maxId) {
            $userId = $maxId;
            $nextUserId = 'E' . str_pad((int)substr($userId, 1) + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextUserId = 'E000001';
        }

        // The rest of your code...

        return view('form.employeeform', compact('nextUserId', 'departments'));

        $departments = department::all();


        return view('form.employeeform', compact('departments'));
    }


    // save data employee

    public function saveRecord(Request $request)
    {
        //    dd($request);
        $request->validate([
            'id' => 'required',
            'd_name' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'full_name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'j_title' => 'required',
            'joinedDate' => 'required',
            'createdDate' => 'required',
            'status' => 'required',
            'account_name' => 'required',
            'account_number' => 'required|numeric|min:5',
            'bank_name' => 'required',
            'branch' => 'required',
            'basic_Salary' => 'required|numeric',
        ]);

        try {
            $this->salary_details(
                $request->input('id'),
                $request->input('type'),
                $request->input('increment_name'),
                $request->input('increment_amount'),
                $request->input('deduction_dates')
            );

            // Create a new employee
            try {
                $employee = new Employee;

                $employee->employee_id = $request->input('id');
                $employee->d_name = $request->input('d_name');
                $employee->f_name = $request->input('f_name');
                $employee->email = $request->input('email');
                $employee->nic = $request->input('nic');
                $employee->c_number = $request->input('c_number');
                $employee->address = $request->input('address');
                $employee->l_name = $request->input('l_name');
                $employee->full_name = $request->input('full_name');
                $employee->dob = $request->input('dob');
                $employee->gender = $request->input('gender');
                $employee->j_title = $request->input('j_title');
                $employee->joinedDate = $request->input('joinedDate');
                $employee->status = $request->input('status');
                $employee->account_name = $request->input('account_name');
                $employee->account_number = $request->input('account_number');
                $employee->bank_name = $request->input('bank_name');
                $employee->branch = $request->input('branch');
                $employee->basic_Salary = $request->input('basic_Salary');
                $employee->createdDate = now();
                $employee->save();
                // dd($employee);

                Toastr::success('Employee record added successfully :)', 'Success');
                return redirect()->route('all/employee/list');
            } catch (\Exception $e) {
                Toastr::error('An error occurred while saving the employee record. Please try again.', 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('An error occurred while saving the record. Please try again.', 'Error');
            return redirect()->back();
        }
    }


    public function salary_details($employee_id, $types, $incrementNames, $incrementAmounts, $dates)
    {
        if (!is_null($types) && count($types) > 0) {
            // Debug statements
            $arraySize = count($types);
            // dd($arraySize,$types[0]);



            // Check if all arrays have the same size

            for ($key = 0; $key < $arraySize; $key++) {
                // dd($key);
                try {
                    $salaryDetail = new SalaryDetail([
                        'employee_id' => $employee_id,
                        'type' => $types[$key],
                        'increment_name' => $incrementNames[$key],
                        'increment_amount' => $incrementAmounts[$key],
                        'date' => Carbon::createFromFormat('d-m-Y', $dates[$key])->format('Y-m-d'),
                    ]);
                    $salaryDetail->timestamps = false;
                    ($salaryDetail);
                    $salaryDetail->save();
                } catch (\Exception $e) {
                    // Log or handle the exception
                    dd("x");
                    dd($e->getMessage());
                }
            }
        }
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
    public function ViewEmployee($user)
    {

        $employee_id = $user;

        $employee = Employee::where('employee_id', $employee_id)->first();
        // dd($employee);
        $salary = SalaryDetail::where('employee_id', $employee_id)->get();

        $departments = department::all();
        $holiday = Holiday::all();
        $findid = $employee->id ?? null;
        $attendances = Attendance::where('employee_id', $findid)->get();

        $employeeHolidayCounts = [];

        $attendances->each(function ($attendance) use ($holiday, &$employeeHolidayCounts, &$user) {
            $attendanceDate = date('d-m-Y', strtotime($attendance->date));

            $attendance->is_holiday = $holiday->contains('date_holiday', $attendanceDate);

            $employeeId = $user;
            $employeeHolidayCounts[$employeeId] = ($employeeHolidayCounts[$employeeId] ?? 0) + ($attendance->is_holiday ? 1 : 0);

            $punchIn = new DateTime($attendance->punch_in);
            $punchOut = new DateTime($attendance->punch_out);
            $workHours = $punchOut->diff($punchIn)->format('%H:%I');

            $regularWorkingHours = new DateTime('10:00');
            $workHours = new DateTime($punchOut->diff($punchIn)->format('%H:%I'));
            $overtime = $workHours > $regularWorkingHours ? $workHours->diff($regularWorkingHours)->format('%H:%I') : '00:00';

            $attendance->overtime = $overtime;
        });
        $attendanceCounts = DB::table('attendances')
            ->select('employee_id', DB::raw('count(*) as attendance_count'))
            ->groupBy('employee_id')
            ->get();

        $curmnth = date('m');
        $curyear = date('Y');
        $totDays = $this->getDaysInMonth($curmnth, $curyear);
        $weekendCount = $this->getWeekendCount($curmnth, $curyear);
        $extraDaysCount = $attendances->filter(function ($attendance) {
            $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
            return $dayOfWeek == 6 || $dayOfWeek == 0;  // Note Saturday (6) or Sunday (0)
        })->count();

        return view('form.employeedetails', compact(
            'employee',
            'salary',
            'attendances',
            'attendanceCounts',
            'holiday',
            'curmnth',
            'curyear',
            'extraDaysCount',
            'employeeHolidayCounts',
            'totDays',
            'weekendCount'
        ));
    }

    public function EditEmployee($user)
    {
        //   dd($user);
        $employee_id = $user;

        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $employee = Employee::where('employee_id', $employee_id)->first();
        $salary = SalaryDetail::where('employee_id', $employee_id)->get();

        // dd($salary);


        $departments = department::all();
        return view('form.edit.employeeedit', compact('employee', 'departments', 'salary'));
    }


    // view edit record
    public function viewRecord($employee_id)
    {
        $permission = DB::table('employees')
            ->join('module_permissions', 'employees.employee_id', '=', 'module_permissions.employee_id')
            ->select('employees.*', 'module_permissions.*')
            ->where('employees.employee_id', '=', $employee_id)
            ->get();
        $employees = DB::table('employees')->where('employee_id', $employee_id)->get();

        return view('form.edit.editemployee', compact('employees', 'permission'));
    }
    // update record employee
    public function updateRecord(Request $request, $employeeId)
    {
        //  dd($request);
        $request->validate([
            'd_name' => 'required',
            'f_name' => 'required',
            'l_name' => 'required',
            'full_name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'j_title' => 'required',
            'joinedDate' => 'required',
            'status' => 'required',
            'account_name' => 'required',
            'account_number' => 'required|numeric|min:5',
            'bank_name' => 'required',
            'branch' => 'required',
            'basic_Salary' => 'required|numeric',
        ]);

        try {
            $this->updateSalaryDetails(
                $request->input('id'),
                $request->input('type'),
                $request->input('increment_name'),
                $request->input('increment_amount'),
                $request->input('deduction_dates')
            );
            // Find the employee by ID
            $employee = Employee::find($employeeId);

            if (!$employee) {
                Toastr::error('Employee not found.', 'Error');
                return redirect()->back();
            }

            // Update employee details
            $employee->d_name = $request->input('d_name');
            $employee->f_name = $request->input('f_name');
            $employee->email = $request->input('email');
            $employee->nic = $request->input('nic');
            $employee->c_number = $request->input('c_number');
            $employee->address = $request->input('address');
            $employee->l_name = $request->input('l_name');
            $employee->full_name = $request->input('full_name');
            $employee->dob = $request->input('dob');
            $employee->gender = $request->input('gender');
            $employee->j_title = $request->input('j_title');
            $employee->joinedDate = $request->input('joinedDate');
            $employee->status = $request->input('status');
            $employee->account_name = $request->input('account_name');
            $employee->account_number = $request->input('account_number');
            $employee->bank_name = $request->input('bank_name');
            $employee->branch = $request->input('branch');
            $employee->basic_Salary = $request->input('basic_Salary');

            // Save the updated employee record
            $employee->save();

            Toastr::success('Employee record updated successfully :)', 'Success');
            return redirect()->route('all/employee/list');
        } catch (\Exception $e) {
            Toastr::error('An error occurred while updating the employee record. Please try again.', 'Error');
            return redirect()->back();
        }
    }
    public function updateSalaryDetails($employee_id, $types, $incrementNames, $incrementAmounts, $dates)
    {
        $arraySize = count($dates);

        // Fetch existing salary records for the employee
        $salaryRecords = SalaryDetail::where('employee_id', $employee_id)->get();

        // Filter the collection to get records with the specific employee_id
        $filteredRecords = $salaryRecords->where('employee_id', $employee_id);

        // Extract only the "id" column values from the filtered records
        $filteredIds = $filteredRecords->pluck('id')->toArray();

        // Iterate over the array of "types" and update or add records
        for ($i = 0; $i < $arraySize; $i++) {
            // Check if there is a record to update
            if (isset($filteredIds[$i])) {
                if ($incrementAmounts[$i] == 0) {
                    // Delete the record if $incrementAmounts is 0
                    SalaryDetail::destroy($filteredIds[$i]);
                } else {
                    // Update the columns in the existing record with the corresponding "id"
                    $recordToUpdate = SalaryDetail::find($filteredIds[$i]);

                    if ($recordToUpdate) {
                        // Sanitize and update the record fields
                        $recordToUpdate->type = $types[$i];
                        $recordToUpdate->increment_name = $incrementNames[$i];
                        $recordToUpdate->increment_amount = $incrementAmounts[$i];

                        // Validate and format the date
                        $formattedDate = Carbon::createFromFormat('d-m-Y', $dates[$i]);
                        if ($formattedDate) {
                            $recordToUpdate->date = $formattedDate->format('Y-m-d');
                        } else {
                            // Handle date format error, e.g., throw an exception
                            // return response()->json(['error' => 'Invalid date format.'], 400);
                        }

                        // Save the updated record
                        $recordToUpdate->save();
                    }
                }
            } else {
                if ($incrementAmounts[$i] != 0) {
                    // Create a new record for cases where there is no corresponding record to update
                    SalaryDetail::create([
                        'employee_id' => $employee_id,
                        'type' => $types[$i],
                        'increment_name' => $incrementNames[$i],
                        'increment_amount' => $incrementAmounts[$i],
                        'date' => Carbon::createFromFormat('d-m-Y', $dates[$i])->format('Y-m-d'),
                    ]);
                }
            }
        }
    }



    // delete record
    public function deleteRecords(Request $request)
    {

        $employee_id = $request->id;
        // dd($employee_id);
        DB::beginTransaction();
        try {

            Employee::where('employee_id', $employee_id)->delete();
            module_permission::where('employee_id', $employee_id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)', 'Success');
            return redirect()->route('all/employee/list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Delete record fail :)', 'Error');
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
        if ($request->employee_id) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->get();
        }
        // search by name
        if ($request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->get();
        }
        // search by name
        if ($request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')
                ->get();
        }

        // search by name and id
        if ($request->employee_id && $request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->get();
        }
        // search by position and id
        if ($request->employee_id && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')
                ->get();
        }
        // search by name and position
        if ($request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')
                ->get();
        }
        // search by name and position and id
        if ($request->employee_id && $request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', '=', 'employees.employee_id')
                ->select('users.*', 'employees.dob', 'employees.gender')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')
                ->get();
        }
        return view('form.allemployeecard', compact('users', 'userList', 'permission_lists'));
    }
    public function employeeListSearch(Request $request)
    {
        
        $employees = Employee::all();
      
        $employeeListSearch = $request->employeeListSearch;
        $data =  Employee::where('full_name','LIKE','%'.$employeeListSearch. '%')->get();


        return view('form.employeelist',compact('data','employees'));

        //dd($request);
        // $users = DB::table('users')
        //     ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //     ->select('users.*', 'employees.dob', 'employees.gender')
        //     ->get();
        // $permission_lists = DB::table('permission_lists')->get();
        // $userList = DB::table('users')->get();

        // // search by id
        // if ($request->employee_id) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
        //         ->get();
        // }
        // // search by name
        // if ($request->name) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('users.name', 'LIKE', '%' . $request->name . '%')
        //         ->get();
        // }
        // // search by department
        // if ($request->department) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('users.department', 'LIKE', '%' . $request->department . '%')
        //         ->get();
        // }

        // // search by name and id
        // if ($request->employee_id && $request->name) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
        //         ->where('users.name', 'LIKE', '%' . $request->name . '%')
        //         ->get();
        // }
        // // search by department and id
        // if ($request->employee_id && $request->department) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
        //         ->where('users.department', 'LIKE', '%' . $request->department . '%')
        //         ->get();
        // }
        // // search by name and department
        // if ($request->name && $request->department) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('users.name', 'LIKE', '%' . $request->name . '%')
        //         ->where('users.department', 'LIKE', '%' . $request->department . '%')
        //         ->get();
        // }
        // // search by name and department and id
        // if ($request->employee_id && $request->name && $request->department) {
        //     $users = DB::table('users')
        //         ->join('employees', 'users.user_id', '=', 'employees.employee_id')
        //         ->select('users.*', 'employees.dob', 'employees.gender')
        //         ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
        //         ->where('users.name', 'LIKE', '%' . $request->name . '%')
        //         ->where('users.department', 'LIKE', '%' . $request->department . '%')
        //         ->get();
        // }
        // return view('form.employeelist', compact('users', 'userList', 'permission_lists'));    




        // $employeeId = $request->input('employee_id');
    


        // $data = DB::table('employees')->where(['employee_id'=>$employeeId])->first();
    
        // return $data;

            



       
    }

    // employee profile with all controller user
    public function profileEmployee($user_id)
    {
        $users = DB::table('users')
            ->leftJoin('personal_information', 'personal_information.user_id', 'users.user_id')
            ->leftJoin('profile_information', 'profile_information.user_id', 'users.user_id')
            ->where('users.user_id', $user_id)
            ->first();
        $user = DB::table('users')
            ->leftJoin('personal_information', 'personal_information.user_id', 'users.user_id')
            ->leftJoin('profile_information', 'profile_information.user_id', 'users.user_id')
            ->where('users.user_id', $user_id)
            ->get();
        return view('form.employeeprofile', compact('user', 'users'));
    }





                                /**    page departments */
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
        try {

            $department = department::where('department', $request->department)->first();
            if ($department === null) {
                $department = department::create([
                    'id' => IdGenerator::generate(['table' => 'departments', 'length' => 7, 'prefix' => 'D']),
                    'department' => $request->department,
                ]);

                DB::commit();
                Toastr::success('Add new department successfully :)', 'Success');
                return redirect()->route('form/departments/page');
            } else {
                DB::rollback();
                Toastr::error('Add new department exits :)', 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add new department fail :)', 'Error');
            return redirect()->back();
        };
    }


    /** update record department */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            // update table departments               
            $department = [
                'id' => IdGenerator::generate(['table' => 'departments', 'length' => 7, 'prefix' => 'D']),
                'department' => $request->department,
            ];
            department::where('id', $request->id)->update($department);

            DB::commit();
            Toastr::success('updated record successfully :)', 'Success');
            return redirect()->route('form/departments/page');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('updated record fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request)
    {
        try {

            department::destroy($request->id);
            Toastr::success('Department deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {

            DB::rollback();
            Toastr::error('Department delete fail :)', 'Error');
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