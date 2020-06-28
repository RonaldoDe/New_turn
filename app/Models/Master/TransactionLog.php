<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $table = 'transaction_log';
    protected $fillable = [
        'id', 'user_id', 'payment_id', 'branch_id', 'service_id', 'action_id', 'order_id', 'transaction_id', 'transaction_state', 'created_at', 'updated_at'
    ];
}
