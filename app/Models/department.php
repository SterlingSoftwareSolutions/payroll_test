<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'department',
    ];
 
    protected $casts = [
        'id' => 'string'
    ];

    public function attendance()        //defined a relationship for display attendance list
    {
        return $this->hasMany(Attendance::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id','id');
    }
}
