<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'holiday_name',
        'date',
    ];

    protected $casts = [
        'id' => 'string'
    ];

}
