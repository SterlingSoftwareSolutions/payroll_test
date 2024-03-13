<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualLeaves extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'year',
        'total_leaves',
        'used_leaves'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}