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
        $gross_salary = $this->basic_salary + $this->br_allowance + $this->fixed_allowance;

        $increments = $this->holiday_payment
                    + $this->incentives
                    + $this->ot
                    + $this->other_increments;

        $deductions = $this->no_pay_leave_deduction
                    + $this->late_deductions
                    + $this->employee_epf
                    + $this->paye
                    + $this->stamp_duty
                    + $this->advance
                    + $this->loan
                    + $this->other_deductions;

        return $gross_salary + $increments - $deductions;
    }
}
