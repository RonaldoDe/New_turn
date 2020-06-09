<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentData extends Model
{
    protected $table = 'payment_data';
    protected $fillable = [
        'user_id', 'payment_method', 'data', 'state'
    ];
}
