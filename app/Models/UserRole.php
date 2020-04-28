<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_has_role';
    public $timestamps=false;
    protected $fillable = [
        'id', 'user_id', 'role_id'
    ];
}
