<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class school extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'schools';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
