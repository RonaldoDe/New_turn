<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterCompany extends Model
{
    protected $table = 'company';
    protected $fillable = [
        'id', 'name', 'description', 'nit', 'email', 'type_id', 'state_id'
    ];
}
