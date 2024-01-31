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

    public function attendance_data($year  = null, $month = null){
        $employee = $this;
        $current_month = $year ?? date('m');
        $current_year = $month ?? date('Y');
        $total_days = $this->getDaysInMonth($current_month, $current_year);
        $attendances = Attendance::where('employee_id', $this->id)->whereMonth('date', $current_month)->whereYear('date', $current_year);
        $weekend_days = $this->getWeekendCount($current_month, $current_year);
        $holidays = Holiday::whereMonth('date_holiday', $current_month)->whereYear('date_holiday', $current_year)->pluck('date_holiday');
        $holiday_working_count = $attendances->whereIn('date', $holidays)->count();
        $working_days = $total_days - $weekend_days - count($holidays);
        $attended_days = $attendances->count();
        $absent_days = $working_days - $attended_days - $holiday_working_count;
        $extra_days_count = $attendances->get()->filter(function ($attendance) {
            $dayOfWeek = Carbon::parse($attendance->date)->dayOfWeek;
            return $dayOfWeek == 6 || $dayOfWeek == 0;  // Note Saturday (6) or Sunday (0)
        })->count();

        return compact(
            'employee',
            'attendances',
            'current_month',
            'current_year',
            'total_days',
            'weekend_days',
            'working_days',
            'attended_days',
            'absent_days',
            'extra_days_count',
            'holidays',
            'holiday_working_count',
        );
    }

    private function getDaysInMonth($month, $year)
    {
        if ($month == "02") {
            return ($year % 4 == 0) ? 29 : 28;
        } elseif (in_array($month, ["01", "03", "05", "07", "08", "10", "12"])) {
            return 31;
        } else {
            return 30;
        }
    }

    private function getWeekendCount($month, $year)
    {
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($startDate, $interval, $endDate);

        $weekendCount = 0;

        foreach ($period as $date) {
            if ($date->format('N') >= 6) {
                $weekendCount++;
            }
        }
        return $weekendCount;
    }
}
