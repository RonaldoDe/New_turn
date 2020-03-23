<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permission';
    protected $fillable = [
        'id', 'name', 'route', 'description', 'module_id'
    ];
}
