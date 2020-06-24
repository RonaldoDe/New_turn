<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    protected $table = 'client_service';
    public $timestamps=false;
    protected $fillable = [
        'employee_id', 'user_id', 'user_service_id', 'dni', 'start_at', 'acepted_by', 'service_id', 'paid_out', 'hours', 'date_start', 'date_end', 'tracking', 'state_id'
    ];

    public function scopeState($query, $state)
    {
        if(!empty($state)){
            $query->where('client_service.state_id', $state);
        }
    }

    public function scopeEmployee($query, $employee)
    {
        if(!empty($employee)){
            $query->where('client_service.employee_id', $employee);
        }
    }

    public function scopeDni($query, $dni)
    {
        if(!empty($dni)){
            $query->where('client_service.dni', 'LIKE', '%'.$dni.'%');
        }
    }

    public function scopeService($query, $service)
    {
        if(!empty($service)){
            $query->where('client_service.service_id', $service);
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
