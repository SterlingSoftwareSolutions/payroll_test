<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id', 'type', 'increment_name', 'increment_amount', 'date',
    ];
}
