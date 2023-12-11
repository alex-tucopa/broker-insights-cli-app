<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $table = 'broker';
    protected $fillable = ['name'];
}
