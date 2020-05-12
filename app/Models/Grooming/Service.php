<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service_list';

    protected $fillable = [
        'name', 'description', 'price_per_hour', 'unit_per_hour', 'hours_max', 'wait_time'
    ];
}
