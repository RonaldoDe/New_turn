<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name', 'last_name', 'address', 'phone', 'email', 'state_id', 'dni', 'phanton_user', 'principal_id', 'business_days'
    ];

    public function scopeName($query, $name)
    {
        if(!empty($name)){
            $query->where('users.name', 'LIKE', '%'.$name.'%');
        }
    }

    public function scopeRange($query, $date_start, $date_end)
    {
        if(empty($date_end) && !empty($date_start)){
            $query->whereBetween('cs.date_start', [$date_start.' 00:00:00', $date_start.' 23:59:59']);
        }else if(!empty($date_end) && !empty($date_start)){
            $query->whereBetween('cs.date', [$date_start.' 00:00:00', $date_end.' 23:59:59']);
        }
    }
}
