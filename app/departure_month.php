<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class departure_month extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'departure_months';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
