<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'company';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
