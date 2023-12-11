<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessEventType extends Model
{
    protected $table = 'business_event_type';
    protected $fillable = ['name'];
}