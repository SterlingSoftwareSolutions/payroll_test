<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importcsv extends Model
{
    use HasFactory;

    protected $fillable = [
        'workID',
        'csv_date',
        'punch_in',
        'punch_out', 
    ];


    protected $dates = [
        'date',
        'punch_in',
        'punch_out',
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
