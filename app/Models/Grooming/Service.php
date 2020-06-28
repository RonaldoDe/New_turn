<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service_list';
    public $timestamps=false;
    protected $fillable = [
        'name', 'description', 'price_per_hour', 'unit_per_hour', 'hours_max', 'wait_time', 'opening_hours', 'state', 'pay_on_line'
    ];
}
