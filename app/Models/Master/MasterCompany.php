<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class MasterCompany extends Model
{
    protected $table = 'company';
    protected $fillable = [
        'id', 'name', 'description', 'nit', 'email', 'type_id', 'state_id'
    ];

    public function scopeName($query, $name)
    {
        if(!empty($name)){
            $query->where('company.name', 'LIKE', '%'.$name.'%');
        }
    }

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
