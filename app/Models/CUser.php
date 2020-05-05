<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name', 'last_name', 'address', 'phone', 'email', 'state_id', 'dni', 'phanton_user', 'principal_id'
    ];

    public function scopeName($query, $name)
    {
        if(!empty($name)){
            $query->where('users.name', 'LIKE', '%'.$name.'%');
        }
    }
}
