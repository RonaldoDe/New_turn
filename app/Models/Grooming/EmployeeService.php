<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class EmployeeService extends Model
{
    protected $table = 'employee_service';

    protected $fillable = [
        'employee_id', 'service_id'
    ];
}
