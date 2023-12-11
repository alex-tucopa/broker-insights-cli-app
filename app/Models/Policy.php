<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $table = 'policy';

    protected $fillable = [
        'broker_id',
        'broker_policy_ref',
        'product_id',
        'business_event_type_id',
        'customer_type_id',
        'broker_customer_ref',
        'insurer_policy_ref',
        'primary_policy_ref',
        'start_date',
        'end_date',
        'effective_date',
        'renewal_date',
        'amount_insured',
        'premium',
        'premium_tax',
        'policy_fee',
        'admin_fee',
        'broker_fee',
    ];
}