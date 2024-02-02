<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'date',
        'punch_in',
        'punch_out',
    ];

    protected $dates = [
        'date',
        'punch_in',
        'punch_out',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'attendances', 'length' => 10, 'prefix' =>'A']);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }

    //  public function holiday()
    // {
    //     return $this->hasMany(Holiday::class, 'holiday_id', 'date');
    // }

    public function holiday()
    {
        return $this->belongsTo(Holiday::class, 'date', 'date_holiday');
    }

    public function duration()
    {
        return $this->punch_out->diffInMinutes($this->punch_in);
    }

    protected $casts = [
        'id' => 'string'
    ];

    
}
