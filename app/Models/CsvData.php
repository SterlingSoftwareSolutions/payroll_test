<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvData extends Model
{
    use HasFactory;

    protected $table = 'csv_data'; // Set the table name if it's different from the model's plural name

    protected $fillable = [
        'User',
        'Work_Id',
        'card_no',
        'date',
        'punch_in',
        'punch_out',
       
      
    ];

}
