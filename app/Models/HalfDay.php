<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HalfDay extends Model
{
    use HasFactory;
    protected $table = 'half_day';
    protected $fillable = [
        'employee_id',
        'half_day_count',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}