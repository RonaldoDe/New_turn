<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterCompany extends Model
{
    protected $table = 'companies';
    protected $fillable = [
        'id', 'name', 'description'
    ];
}
