<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class ClientService extends Model
{
    protected $table = 'client_service';
    public $timestamps=false;
    protected $fillable = [
        'employee_id', 'user_id', 'user_service_id', 'dni', 'start_at', 'acepted_by', 'service_id', 'paid_out', 'hours', 'date_start', 'date_end', 'state_id'
    ];
}
