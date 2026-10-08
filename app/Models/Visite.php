<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    protected $fillable = [
        'date',
        'total',
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'integer',
    ];
}
