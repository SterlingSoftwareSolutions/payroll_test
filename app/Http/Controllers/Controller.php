<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDepartments()
    {   
        
        $departments = Department::all(); // Fetch departments from the database
        // $lastDepartmentId = Department::al();
        // $newDepartmentId = $lastDepartmentId + 1;
        // dd($departments);
        return response()->json($departments);
    }

   

        //         public function showDepartmentForm()
        //     {
        //     $lastDepartmentId = getLastDepartmentIdFromDatabase(); // Replace with actual logic


        //     $newDepartmentId = $lastDepartmentId + 1;


        //     return view('your.view.name', compact('newDepartmentId'));
        // }

}
