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
                    + $this->extra_days_payment
                    + $this->incentives
                    + $this->ot
                    + $this->other_increments;

        $deductions = $this->no_pay_leave_deduction
                    + $this->late_deduction
                    + $this->employee_epf
                    + $this->paye
                    + $this->stamp_duty
                    + $this->advance
                    + $this->loan
                    + $this->other_deductions;

        return $gross_salary + $increments - $deductions;
    }
    public function calculateTax($netSalary)
    {
        $tax = 0;
        $grossSalary = $netSalary;

        if ($grossSalary > 308333) {
            $tax += ($grossSalary - 308333) * 0.36;
            $grossSalary = 308333;
        }
        if ($grossSalary > 266667) {
            $tax += ($grossSalary - 266667) * 0.3;
            $grossSalary = 266667;
        }
        if ($grossSalary > 225000) {
            $tax += ($grossSalary - 225000) * 0.24;
            $grossSalary = 225000;
        }
        if ($grossSalary > 183333) {
            $tax += ($grossSalary - 183333) * 0.18;
            $grossSalary = 183333;
        }
        if ($grossSalary > 141667) {
            $tax += ($grossSalary - 141667) * 0.12;
            $grossSalary = 141667;
        }
        if ($grossSalary > 100000) {
            $tax += ($grossSalary - 100000) * 0.06;
        }

        return $tax;
    }
}
