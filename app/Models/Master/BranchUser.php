<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class BranchUser extends Model
{
    protected $table = 'branch_user';
    protected $fillable = [
        'id', 'user_id', 'branch_id'
    ];
}
