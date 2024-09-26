<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Hash;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class RegisterController extends Controller
{
    public function register()
    {
        $users = User::all();
       

        $userId = IdGenerator::generate(['table' => 'users', 'length' => 6, 'prefix' => 'U']);
        
        $role = DB::table('role_type_users')->get();
        return view('auth.register',compact('role',));
    }
    public function storeUser(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);
        
            if ($validator->fails()) {
                // Validation failed
                //return dd($request);
               
            }
            $dt = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();
          $userId = IdGenerator::generate(['table' => 'users', 'length' => 6, 'prefix' => 'U']);
    
            // Create user
            User::create([
                'user_id' => $userId,
                'name' => $request->name,
                'avatar' => $request->image,
                'email' => $request->email,
                'join_date' => $todayDate,
                'role_name' => "Admin",
                'status' => 'Active',
                'password' => Hash::make($request->password),
            ]);
        
            Toastr::success('Create new account successfully :)', 'Success');
            return redirect('login');
        } catch (\Exception $e) {
            // Handle the exception, log it, or display an error message
            Toastr::error('An error occurred while creating the account. Please try again later.', 'Error');
            return redirect()->back();
        }
        
    }
}
