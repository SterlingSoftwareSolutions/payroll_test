<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    use HasFactory;

    protected $fillable = ['title_name'];

    // Define any relationships here
    public function departmentTitleStatuses()
    {
        return $this->hasMany(DepartmentTitleStatus::class);
    }
}