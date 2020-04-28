<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTurn extends Model
{
    protected $table = 'client_turn';
    public $timestamps=false;
    protected $fillable = [
        'employee_id', 'user_id', 'user_turn_id', 'dni', 'date', 'start_at', 'finished_at', 'finished_by_id', 'service_id', 'today', 'turn_number', 'c_return', 'paid_out', 'state_id'
    ];
}
