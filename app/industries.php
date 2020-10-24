<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class industries extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'industries';
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
