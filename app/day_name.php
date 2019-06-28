<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day_name extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'day_name';
    protected $fillable = [
        'abbrev', 'name'
    ];
}
