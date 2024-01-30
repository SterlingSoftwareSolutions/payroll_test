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
        'address',
        'account_name',
        'account_number',
        'bank_name',
        'branch',
        'basic_Salary',
    ];

    protected $casts = [
        'dob' => 'date',
        'joinedDate' => 'date',
        'createdDate' => 'date',
    ];

    // Employee.php
    public function bankDetails()
    {
        return $this->hasMany(BankDetail::class);
    }

    public function getNameAttribute()
    {
        return $this->attributes['employee_name']; // adjust based on your attribute name
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
