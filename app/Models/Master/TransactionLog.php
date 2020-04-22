<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $table = 'transcation_log';
    protected $fillable = [
        'id', 'user_id', 'payment_id', 'branch_id', 'service_id', 'action_id', 'created_at', 'updated_at'
    ];
}
