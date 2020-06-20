<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class EmployeeTypeService extends Model
{
    protected $table = 'employee_type_service';

    protected $fillable = [
        'employee_type_id', 'service_id'
    ];
}
