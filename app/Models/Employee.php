<?php

namespace App\Models;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'employee_id',
        'work_id',
        'etf_no',
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
        'appointmentDate',
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
        'appointmentDate' => 'date',
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
        return $this->belongsTo(department::class, 'd_name');
    }

    public function attendance_data($year  = null, $month = null){
        $department = $this->department->department;

        // Month details
        $current = Carbon::create($year ?? now()->subMonth()->year, $month ?? now()->subMonth()->month);
        $month_days_count = $current->daysInMonth;
        $month_weekends_count = $current->diffInDaysFiltered(function (Carbon $date){
            return $date->isSaturday() || $date->isSunday();
        }, $current->copy()->lastOfMonth());
        $month_holidays = Holiday::whereMonth('date_holiday', $current->month)->whereYear('date_holiday', $current->year)->get();
        $month_holiday_weekends = with(clone $month_holidays)->filter(function ($holiday){
            return $holiday->date_holiday->isSaturday() || $holiday->date_holiday->isSunday();
        });
        $work_days = $month_days_count - $month_weekends_count;
        $work_hours = $department == 'Local' ? 9 : 10;

        // Employee details
        $attendances = Attendance::where('employee_id', $this->id)->whereMonth('date', $current->month)->whereYear('date', $current);

        $days_worked = with(clone $attendances)->whereNotIn('date', $month_holidays->pluck('date_holiday'))->get()->filter(function($attendance) use ($department){
            if($department == 'Local'){
                return !$attendance->date->isSunday();
            }
            return !$attendance->date->isSaturday() && !$attendance->date->isSunday();
        });
        
        $days_worked_holiday = with(clone $attendances)->whereIn('date', $month_holidays->pluck('date_holiday'))->get();
        
        $days_worked_weekend = with(clone $attendances)->get()->filter(function($attendance){
            return $attendance->date->isSaturday() || $attendance->date->isSunday();
        });
        
        $days_worked_holiday_weekend = with(clone $days_worked_holiday)->filter(function ($attendance) use ($department){
            if($department == 'Local'){
                return $attendance->date->isSunday();
            }
            return $attendance->date->isSaturday() || $attendance->date->isSunday();
        });
        
        $no_pay_leaves = $work_days - $days_worked->count() - $days_worked_holiday->count();
        
        $late_minutes = with(clone $attendances)->get()->sum(function ($attendance) use ($department, $work_hours){
            if($department == 'Local' && $attendance->date->isSaturday()){
                $diff = 5 * 60 - $attendance->duration();
            } else{
                $diff = $work_hours * 60 - $attendance->duration();
            }
            return $diff > 0 ? $diff : 0;
        });

        $ot_minutes = with(clone $attendances)->get()->sum(function ($attendance) use ($department, $work_hours){
            if($department == 'Local' && $attendance->date->isSaturday()){
                $diff = $attendance->duration() - 5 * 60;
            } else{
                $diff = $attendance->duration() - $work_hours * 60;
            }
            return $diff > 0 ? $diff : 0;
        });

        return compact(
            'month_days_count',
            'month_weekends_count',
            'month_holidays',
            'work_days',
            'work_hours',
            'days_worked',
            'days_worked_holiday',
            'days_worked_weekend',
            'days_worked_holiday_weekend',
            'no_pay_leaves',
            'late_minutes',
            'ot_minutes',
        );

    }
}
