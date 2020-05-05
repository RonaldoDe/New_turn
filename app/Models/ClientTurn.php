<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTurn extends Model
{
    protected $table = 'client_turn';
    public $timestamps=false;
    protected $fillable = [
        'employee_id', 'user_id', 'user_turn_id', 'dni', 'date', 'start_at', 'started_by', 'finished_at', 'finished_by_id', 'service_id', 'today', 'turn_number', 'c_return', 'paid_out', 'state_id'
    ];

    public function scopeState($query, $state)
    {
        if(!empty($state)){
            $query->where('state_id', $state);
        }
    }

    public function scopeEmployee($query, $employee)
    {
        if(!empty($employee)){
            $query->where('finished_by_id', $employee);
        }
    }

    public function scopeDni($query, $dni)
    {
        if(!empty($dni)){
            $query->where('dni', $dni);
        }
    }

    public function scopeService($query, $service)
    {
        if(!empty($service)){
            $query->where('service_id', $service);
        }
    }

    public function scopeRange($query, $date_start, $date_end)
    {
        if(empty($date_end) && !empty($date_start)){
            $query->whereBetween('date', [$date_start.' 00:00:00', $date_start.' 23:59:59']);
        }else if(!empty($date_end) && !empty($date_start)){
            $query->whereBetween('date', [$date_start.' 00:00:00', $date_end.' 23:59:59']);
        }
    }
}
