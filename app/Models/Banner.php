<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';
    protected $fillable = [
        'id', 'name', 'description', 'img_short', 'img_median', 'img_big', 'link', 'state_id'
    ];
}
