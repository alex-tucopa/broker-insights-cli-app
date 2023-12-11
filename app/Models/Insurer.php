<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    public $timestamps = false;
    protected $table = 'insurer';
    protected $fillable = [
        'name',
        'description'
    ];
}
