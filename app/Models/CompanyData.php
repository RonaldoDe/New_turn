<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyData extends Model
{
    protected $table = 'company_data';

    protected $fillable = [
        'turns_number', 'min_turns', 'current_return', 'company_id', 'api_k', 'api_l', 'mer_id', 'acc_id', 'pay_on_line'
    ];
}
