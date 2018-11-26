<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class benefits extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'benefits';
    protected $fillable = [
        'name'
    ];

    public $timestamps = true;
}
