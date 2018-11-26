<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class departure_year extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'departure_years';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
