<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTitleStatus extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'job_title_id', 'job_status_id'];

    // Define any relationships here
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }

    public function jobStatus()
    {
        return $this->belongsTo(JobStatus::class);
    }
}