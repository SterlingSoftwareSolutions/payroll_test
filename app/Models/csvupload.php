<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class csvupload extends Model
{
    use HasFactory;

    protected $table = 'csv_data'; // Set the table name if it's different from the model's plural name

    protected $fillable = [
        'user',
        'work_id',
        'card_no',
        'date',
        'punch_in',
        'punch_out',
        'event_code',
        'IN/OUT',
    ];

}
