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
        'j_status',
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
        'workingHours',
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

    //Below functions use to calculate attendance report edit page
    public function attendance_data($year  = null, $month = null)
    {
        // mata baninna epa sama weena sudesh eka eka welawata eka eka ewa kiyanawa
        // ekai mehema karanna une , sry....
        $department = $this->department->department;
        // Month details
        // $current = Carbon::create($year ?? now()->subMonths(2)->year, $month ?? now()->subMonths(2)->month);
        $current = Carbon::create($year ?? now()->subMonth()->year, $month ?? now()->subMonth()->month);
        $attendances = Attendance::where('employee_id', $this->id)->whereMonth('date', $current->month)->whereYear('date', $current);

        $remove_late = Attendance::where('employee_id', $this->id)
            ->whereMonth('date', $current->month)
            ->whereYear('date', $current->year)
            ->where('is_half_day', true)
            ->get();

        // Sum the "late" minutes as decimal values
        $remove_late_minutes = $remove_late->sum(function ($attendanceRecord) {
            $lateTime = $attendanceRecord->late;

            // Split the "late" time into hours, minutes, and seconds
            $timeParts = explode(':', $lateTime);

            // Convert to decimal minutes
            $totalMinutes = ($timeParts[0] * 60) + $timeParts[1] + ($timeParts[2] / 60);

            return $totalMinutes;
        });

        $work_half_day = Attendance::where('employee_id', $this->id)
            ->whereMonth('date', $current->month)
            ->whereYear('date', $current->year)
            ->where('is_half_day', true)
            ->count();

        $month_days_count = $current->daysInMonth;
        $firstOfMonth = $current->copy()->firstOfMonth();
        $lastOfMonth = $current->copy()->lastOfMonth();

        $work_hours = $this->workingHours == "6day" ? 9 : 10;
        $weekendCount = 0;


        for ($date = $firstOfMonth; $date->lte($lastOfMonth); $date->addDay()) {
            if ($date->isWeekend()) {
                if ($work_hours == 9) {
                    if ($date->dayOfWeek == Carbon::SATURDAY) {
                        $weekendCount += 0.5;
                    } else {
                        $weekendCount += 1;
                    }
                } elseif ($work_hours == 10) {
                    $weekendCount += 1;
                }
            }
        }

        // dd($weekendCount);

        $holiday_dates = Holiday::whereMonth('date_holiday', $current->month)
            ->whereYear('date_holiday', $current->year)
            ->pluck('date_holiday')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        // dd($holiday_dates);

        $dates = Attendance::where('employee_id', $this->id)
            ->whereMonth('date', $current->month)
            ->whereYear('date', $current->year)
            ->pluck('date')
            ->filter(function ($date) use ($work_hours) {
                return !$date->isWeekend() || ($work_hours == 9 && $date->dayOfWeek == Carbon::SATURDAY);
            })
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        // dd($dates);
        $datesWithoutWeekends = collect($dates)->filter(function ($date) use ($work_hours) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->dayOfWeek == Carbon::SUNDAY && $work_hours == 9) {
                return false;
            }
            if (($carbonDate->dayOfWeek == Carbon::SATURDAY || $carbonDate->dayOfWeek == Carbon::SUNDAY) && $work_hours == 10) {
                return false;
            }
            return true;
        })->toArray();

        // dd($datesWithoutWeekends);

        $holiday_dates = Holiday::whereMonth('date_holiday', $current->month)
            ->whereYear('date_holiday', $current->year)
            ->pluck('date_holiday')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
        $holiday_datescount = count($holiday_dates);
        $datesWithoutWeekends = collect($dates)->filter(function ($date) use ($work_hours, $holiday_dates) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->dayOfWeek == Carbon::SUNDAY && $work_hours == 9) {
                return false;
            }
            if (($carbonDate->dayOfWeek == Carbon::SATURDAY || $carbonDate->dayOfWeek == Carbon::SUNDAY) && $work_hours == 10) {
                return false;
            }
            return !in_array($date, $holiday_dates);
        })->toArray();

        $countWithoutWH = count($datesWithoutWeekends);



        $month_weekends_count = $weekendCount;
        $month_holidays = Holiday::whereMonth('date_holiday', $current->month)->whereYear('date_holiday', $current->year)->get();
        // dd($month_holidays);
        $month_holiday_weekends = $month_holidays->filter(function ($holiday) {

            return $holiday->date_holiday->isSaturday() || $holiday->date_holiday->isSunday();
        });


        $holydayf = $holiday_datescount - $month_holiday_weekends->count();

        $work_days = $month_days_count - ($month_weekends_count + $holydayf);
        // dd($work_days);

        $attendances = Attendance::where('employee_id', $this->id)
            ->whereMonth('date', $current->month)
            ->whereYear('date', $current->year);

        // dd($attendances);
        $days_worked = with(clone $attendances)->whereNotIn('date', $month_holidays->pluck('date_holiday'))->get()->filter(function ($attendance) use ($department) {
            if ($this->workingHours == "6day") {
                return !$attendance->date->isSunday();
            }
            return !$attendance->date->isSaturday() && !$attendance->date->isSunday();
        });
        // dd($days_worked->count());

        $daysInMonth = $current->daysInMonth;

        // Initialize a count variable for Saturdays
        $count_worked_saturdays = 0;

        // Iterate over each day of the month
        $days_worked_saturdays = $attendances->get()->filter(function ($attendance) use ($month_holidays) {
            return $attendance->date->isWeekend() && !in_array($attendance->date->format('Y-m-d'), $month_holidays->pluck('date_holiday')->toArray());
        })->filter(function ($attendance) {
            return $this->workingHours == "6day" && $attendance->date->dayOfWeek == Carbon::SATURDAY;
        });

        $count_worked_saturdays = $days_worked_saturdays->count();
        // dd($count_worked_saturdays);
        if ($this->workingHours == "6day") {
            $days_worked = $days_worked->count() - ($count_worked_saturdays / 2);
            if ($days_worked < 0) {
                $days_worked = 0;
            }
        } else {
            $days_worked = $days_worked->count();
            // dd("5" + $days_worked);
        }

        $days_worked_holiday = with(clone $attendances)->whereIn('date', $month_holidays->pluck('date_holiday'))->get();
        // dd($days_worked_holiday);
        $days_worked_weekend = with(clone $attendances)->get()->filter(function ($attendance) {
            if ($this->workingHours == "6day") {
                return $attendance->date->isSunday();
            } else {
                return $attendance->date->isSaturday() || $attendance->date->isSunday();
            }
        });

        $days_worked_holiday_weekend = with(clone $days_worked_holiday)->filter(function ($attendance) use ($department) {

            return $attendance->date->isSaturday() || $attendance->date->isSunday();
        });
        $days_worked = $days_worked + $days_worked_holiday_weekend->count();
        // dd($days_worked_holiday);
        $days_worked = $days_worked - ($work_half_day / 2);

        $no_pay_leaves = $work_days - $days_worked;
        // dd($no_pay_leaves);

        $ot_minutesx = with(clone $attendances)->get()->map(function ($attendance) {
            // Ensure the OT field is not null or empty
            $otTime = $attendance->OT ?? '00:00:00'; // Default to '00:00:00' if OT is null or empty

            // Split the time into hours, minutes, and seconds
            $timeParts = explode(':', $otTime);

            // Ensure all parts exist (default to 0 if missing)
            $hours = isset($timeParts[0]) ? (int)$timeParts[0] : 0;
            $minutes = isset($timeParts[1]) ? (int)$timeParts[1] : 0;
            $seconds = isset($timeParts[2]) ? (int)$timeParts[2] : 0;

            // Convert time to total overtime minutes (correctly handling seconds)
            $totalMinutes = ($hours * 60) + $minutes + round($seconds / 60);

            // Return the individual overtime minute value
            return $totalMinutes;
        })->toArray();

        // Calculate the sum of overtime minutes
        $ot_minutes = array_sum($ot_minutesx);
        // round up 30 min
        $ot_minutes = floor($ot_minutes / 30) * 30;

        // Dump the array and the sum
        // dd($ot_minutes, $ot_minutesx);

        $late_minutes = with(clone $attendances)->get()->sum(function ($attendance) {
            // Get the "late" time from the current attendance record
            $lateTime = $attendance->late;

            // Split the time into hours, minutes, and seconds
            $timeParts = explode(':', $lateTime);

            // Calculate the total late minutes
            $totalMinutes = ($timeParts[0] * 60) + $timeParts[1] + ($timeParts[2] / 60);

            return $totalMinutes;
        });

        $annualLeaves = $this->calculate_annual_leaves($current->year);

        $days_worked_holiday = $days_worked_holiday->count() - $days_worked_holiday_weekend->count();

        $late_minutes = $late_minutes - $remove_late_minutes;

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
            'annualLeaves',
            'current',
            'work_half_day',
            'remove_late_minutes'
        );
    }

    public function annual_leaves($year = null)
    {
        if (!$year) {
            $year = now()->year;
        }

        $annual_leaves = AnnualLeaves::where('employee_id', $this->id)->where('year', $year)->first();
        if (!$annual_leaves) {
            AnnualLeaves::create([
                'employee_id' => $this->id,
                'year' => $year,
                'total_leaves' => $this->calculate_annual_leaves($year)
            ]);
        }
    }

    private function calculate_annual_leaves($year)
    {
        $joinedDate = $this->joinedDate;
        $years = now()->diffInYears($joinedDate);
        if ($years >= 2) {
            return 14;
        } elseif ($years < 1) {
            return 0;
        }

        $januaryFirst = Carbon::parse('January 1, ' . $joinedDate->year);
        $aprilFirst = Carbon::parse('April 1, ' . $joinedDate->year);
        $julyFirst = Carbon::parse('July 1, ' . $joinedDate->year);
        $octoberFirst = Carbon::parse('October 1, ' . $joinedDate->year);

        if ($joinedDate->gte($januaryFirst) && $joinedDate->lt($aprilFirst)) {
            $annualLeaves = 14;
        } elseif ($joinedDate->gte($aprilFirst) && $joinedDate->lt($julyFirst)) {
            $annualLeaves = 10;
        } elseif ($joinedDate->gte($julyFirst) && $joinedDate->lt($octoberFirst)) {
            $annualLeaves = 7;
        } elseif ($joinedDate->gte($octoberFirst) && $joinedDate->lte(Carbon::parse('December 31'))) {
            $annualLeaves = 4;
        } else {
            $annualLeaves = 0;
        }

        return $annualLeaves;
    }
}
