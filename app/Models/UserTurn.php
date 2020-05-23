<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTurn extends Model
{
    protected $table = 'user_turn';
    public $timestamps=false;
    protected $fillable = [
        'user_id', 'branch_id', 'service_type', 'created_at', 'state'
    ];
}
