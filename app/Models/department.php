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


    public function attendanceReportSearch()
{
    $departments = Department::all(); 
    return view('reports.attendance-report', ['departments' => $departments]);
}
}
