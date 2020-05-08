<?php

namespace App\Models\Grooming;

use Illuminate\Database\Eloquent\Model;

class GCompanyData extends Model
{
    protected $table = 'company_data';

    protected $fillable = [
        'company_id', 'opening_hours', 'api_k', 'api_l', 'mer_id', 'acc_id', 'pay_on_line'
    ];
}
