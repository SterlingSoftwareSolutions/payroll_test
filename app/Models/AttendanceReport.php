<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceReport extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'date' => 'date',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
