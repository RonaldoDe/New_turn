<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class EmployeeTypeEmployee extends Model
{
    protected $table = 'employee_type_employee';

    protected $fillable = [
        'employee_type_id', 'employee_id'
    ];
}
