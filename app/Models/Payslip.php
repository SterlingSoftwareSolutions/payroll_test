<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'date' => 'date'
    ];
    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function net_salary(){
        return $this->basic_salary;
    }
}
