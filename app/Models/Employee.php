<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'employee_id',
        'd_name',
        'f_name',
        'l_name',
        'full_name',
        'dob',
        'gender',
        'email',
        'nic',
        'c_number',
        'j_title',
        'joinedDate',
        'createdDate',
        'status',
        'description',
        'account_name',
        'account_number',
        'bank_name',
        'branch',
    ];

    protected $casts=[
        'dob' => 'date',
        'joinedDate'=> 'date',
        'createdDate'=> 'date',
    ];
    // Employee.php
    public function bankDetails()
    {
        return $this->hasMany(BankDetail::class);
    }


    
// public function holiday()
// {
//     return $this->hasMany(Holiday::class);
// }


   




}
