<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $table = 'branch_office';
    protected $fillable = [
        'id', 'name', 'description', 'nit', 'email', 'city', 'longitude', 'latitude', 'address', 'phone', 'db_name', 'close', 'hours_24', 'state_id', 'company_id', 'created_at', 'updated_at'
    ];
}
