<?php

namespace App\Models;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'employee_id',
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

    protected $casts = [
        'date' => 'date',
    ];
}
