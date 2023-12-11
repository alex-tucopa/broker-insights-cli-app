<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $fillable = [
        'insurer_id',
        'product_type_id',
        'name'
    ];
}
