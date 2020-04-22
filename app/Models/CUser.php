<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name', 'last_name', 'address', 'phone', 'email', 'password', 'state_id', 'dni', 'phanton_user', 'principal_id'
    ];
}
