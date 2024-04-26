<?php

namespace App\Models;

use App\Models\AttendanceReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['report_id', 'user_id', 'note'];
    protected $table = 'notes'; // Assuming your table name is 'notes'

    // Define the relationships if needed
    public function report()
    {
        return $this->belongsTo(AttendanceReport::class, 'report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
