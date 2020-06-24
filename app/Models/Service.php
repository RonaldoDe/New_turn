<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service_list';
    protected $fillable = [
        'name', 'description', 'time', 'price', 'opening_hours', 'state'
    ];
}
