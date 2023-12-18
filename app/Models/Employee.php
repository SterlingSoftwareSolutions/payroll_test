<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'f_name',
        'l_name',
        'full_name',
        'employee_id',
        'email',
        'dob',
        'nic',
        'c_number',
        'j_title',
        'd_name',
        'joinedDate',
        'createdDate',
        'status',
        'gender',
        'description',
        
    ];

    protected $casts=[
        'dob' => 'date',
    ];


    // public function department()
    // {
    //     return $this->belongsTo(Department::class, 'employee_id');
    // }
    

    // public function department()
    // {
    //    // return $this->belongsTo(Department::class);
       
    //     return $this->belongsTo(Department::class,'department_id');

    // }
    public function department()
    {
        return $this->belongsTo(department::class, 'department_id', 'id');
    }
}
