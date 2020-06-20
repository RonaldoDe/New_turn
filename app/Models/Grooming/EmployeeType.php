<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    protected $table = 'employee_type';

    protected $fillable = [
        'name', 'description', 'state'
    ];
}
